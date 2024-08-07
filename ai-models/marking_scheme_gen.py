# -*- coding: utf-8 -*-
"""marking_scheme_gen.ipynb

Automatically generated by Colab.

Original file is located at
    https://colab.research.google.com/drive/1eOg57jaK2UprZnTNyosHoO9Pw98DwmYS

#### Importing and installing needed libraries
"""

import os

!pip install -U torch
!pip install PyMuPDF # for reading PDFs with Python
!pip install tqdm # for progress bars
!pip install sentence-transformers # for embedding models
!pip install accelerate
!pip install bitsandbytes
!pip install flash-attn --no-build-isolation
!pip install langchain #might not be used but to create pipelines
!pip install openai #same case as langchain
!pip install --upgrade google-api-python-client google-auth-httplib2 google-auth-oauthlib
!pip install --upgrade --quiet  google-api-python-client google-auth-httplib2 google-auth-oauthlib

!pip install -q torch transformers transformers accelerate bitsandbytes langchain sentence-transformers faiss-gpu openpyxl pacmap

"""#### Get and prepare our data
Get our various documents and put them together
"""

import os
import requests
import langchain
import openai

os.environ["OPENAI_API_KEY"] = ""

#connect google drive
from google.colab import drive
drive.mount("/content/drive")

#test if the google drive connection has been made
!ls "/content/drive/MyDrive/DME/Build/Train"

#set file path for content
path_main_doc = "/content/drive/MyDrive/DME/Build/Train/CRE.txt"
path_text_file1 = "/content/drive/MyDrive/DME/CRE Input text/test.txt"
path_text_file2 = "/content/drive/MyDrive/DME/CRE Input text/test2.txt"

"""### Preprocessing the data

"""

#function to read, clean, fix up and chunk the first text
def read_and_chunk_text1(path: str)-> list[dict]:
  #create an instance of the splitter
  text_splitter = RecursiveCharacterTextSplitter(
    chunk_size = 1200,
    chunk_overlap = 0,
    length_function = len)

  #read the data
  with open(path) as sample:
    contents = sample.read()

  #chunk the data
  contents_split = text_splitter.split_text(contents)

  #fix chunks with overlap
  contents_split[33] = '''
  b)	Effects of sin on Adam and Eve
  i.	They started dying yet they were to live forever
  ii.	They became afraid of God/they hide when He called them.
  iii.	They lost authority over the other creation
  iv.	Man was to rule over the woman/inequality between man and woman set in.
  v.	The woman was to be in pain when giving birth
  vi.	There developed enemity between the human beings and the serpent
  vii.	They became embarrassed because of their nakedness
  viii.	They were expelled out of the Garden of Eden /separated with God
  ix.	They developed mistrust between man and woman. (4x2=8 mks)
  c)	How the church helps to bring back members who have fallen from the faith.
  i.	By visiting the/inviting them to their homes
  ii.	By being patient/forgiving them
  iii.	By evangelizing to them/preach/teaching
  iv.	By guiding and counseling them/referring them to experts according to their needs.
  v.	Praying for them
  vi.	By inviting them back to church
  vii.	By encouraging them to repent/confess
  viii.	By offering material needs/aids	(5x1= 5 mks)
  '''

  contents_split[32] = '''
  1.	The teaching about human beings from the biblical creation accounts
  i.	Human beings are created in the image/likeliness of God
  ii.	They have been given authority /domination over God creation.
  iii.	They communicate /fellowships with God.
  iv.	They are special/the greatest creation of God
  v.	They have the ability to think /reason/make choices/decision sin their lives
  vi.	They are blessed by God
  vii.	They have give a special place to stay/Garden of Eden
  viii.	Human beings are to use other creation/plant for their benefits
  ix.	They are to take care of the creation till the land work
  x.	Human beings are to procreate/multiply through marriage.
  xi.	Man and woman era to compliment/provide companionship for each other.
  xii.	Human beings are Gods creation/male and female.
  xiii.	The woman is created out of hetmans rib (7x1= 7 mks)
  '''

  #delete chunks with little to no data
  del contents_split[98]
  del contents_split[115]

  assert (len(contents_split)) == 115


  text_stat = []
  for i in range(len(contents_split)):
    contents_split[i] = contents_split[i].replace('\uf076', ' ').replace('\t', ' ')
    text_stat.append(
              {
                  "chunk_char_count": len(contents_split[i]),
                  "chunk_word_count": len(contents_split[i].split(" ")),
                  "chunk_token_count": len(contents_split[i]) / 4,
                  "text": contents_split[i]
              })


  return text_stat

#function to read, clean, fix up and chunk the second text
def read_and_chunk_text2(path: str)-> list[dict]:
  #create an instance of the splitter
  text_splitter = RecursiveCharacterTextSplitter(
    chunk_size = 1200,
    chunk_overlap = 0,
    length_function = len)

  #read the data
  with open(path) as sample:
    contents = sample.read()

  #chunk the data
  contents_split = text_splitter.split_text(contents)

  #fix chunks with overlap
  contents_split[119] = '''
b)	Outline the events that took place from the time Jesus was arrested up to the time he was
sentenced to die
i.	Jesus was taken to the house of the high priest
ii.	Peter denied Jesus three times
iii.	Jesus was mocked/ beaten /blindfolded
iv.	Jesus was taken to the Sanhendrin /they made religious accusations against him
v.	Jesus was taken to Pilate/they made political accusations against him
vi.	He was sent to Herod who questioned Jesus/ridiculed/dressed him in a royal robe
vii.	Pilate said that he had not found Jesus guilty
viii.	Pilate chose to have Jesus flogged/chastised
ix.	The crowd shouted that Jesus should be crucified/demanded release of Barabas
x.	Pilate surrendered Jesus to be crucified (7x1=7mks)
  '''

  contents_split[120] = '''
 c)	State seven lessons Christians learn from the suffering and death of Jesus
i.	Christians should have faith in God
ii.	They should endure suffering /be ready to be rejected
iii.	They should forgive their enemies
iv.	Christians should repent /confess their sins
v.	They should be obedient /loyal to God
vi.	They should stand for the truth at all times
vii.	Christians should sacrifice for the service of others
viii.	They should be prayerful/pray for others
ix.	Christians should witness /surrender to the Lordship of Christ
x.	Christians should be courageous /brave (7x1=7mks)
  '''

  #delete chunks with little to no data
  del contents_split[87]

  assert (len(contents_split)) == 126

  text_stat = []
  for i in range(len(contents_split)):
    contents_split[i] = contents_split[i].replace('\uf076', ' ').replace('\t', ' ')
    text_stat.append(
              {
                  "chunk_char_count": len(contents_split[i]),
                  "chunk_word_count": len(contents_split[i].split(" ")),
                  "chunk_token_count": len(contents_split[i]) / 4,
                  "text": contents_split[i]
              })

  return text_stat

#a function to read our needed content into a single variable
def read_and_chunk_doc(path: str) -> list[dict]:
  #create an instance of the splitter function
  text_splitter = RecursiveCharacterTextSplitter(
    chunk_size = 1100,
    chunk_overlap = 0,
    length_function = len
  )

  #read the data
  with open(path) as train:
    content = train.read()

  content_split = text_splitter.split_text(content)

  #split our data into various dictionaires
  chunks_text = []
  for i in range(len(content_split)):
    chunks_text.append(
              {
                  "chunk_char_count": len(content_split[i]),
                  "chunk_word_count": len(content_split[i].split(" ")),
                  "chunk_token_count": len(content_split[i]) / 4,
                  "text": content_split[i]
              })

  return chunks_text

from tqdm.auto import tqdm
from langchain.text_splitter import RecursiveCharacterTextSplitter

#read data from the various documents and put them all together
chunks_text = read_and_chunk_doc(path_main_doc)
text1 = read_and_chunk_text1(path_text_file1)
text2 = read_and_chunk_text2(path_text_file2)

chunks_text = chunks_text + text1 + text2

print(f"Number of chunks we have: {len(chunks_text)}")

chunks_text[1164]

"""### Some rough EDA on our text"""

!pip install pandas
!pip install numpy

import pandas as pd

df = pd.DataFrame(chunks_text)
df.head()

#get some stats about the data
df.describe().round(2)

"""From the describtion we've seen so far, we have an average of 196 tokens and a max token count of 299. The model used for embedding, "all-mpnet-base-V2" has a max token limit of 384 which leaves us a lot of room for embedding and padding

### The code below can be ignored.
After experimenting and refinement, the chunking method chosen for use is Langchain's `RecursiveCharacterTextSplitter` that splits the text on` ["\n\n", "\n", ". "]`. The method below employ sentence chunking splitting only on `[". "]` followed by chunking the discorved sentences together.

#### Some more preprocessing

Sentence chunking
"""

!pip install spacy

from spacy.lang.en import English
nlp = English()

config = {"punct_chars": ["\n","/"]}
nlp.add_pipe("sentencizer")

doc = nlp((pages_text[11]).get("text"))
#assert len(list(doc.sents)) == 1

list(doc.sents)

from spacy.lang.en import English
nlp = English()
nlp.add_pipe("sentencizer")
for item in tqdm(pages_text):
  item["sentences"] = list(nlp(item["text"]).sents)
  item["sentences"] = [str(sentence) for sentence in item["sentences"]]
  item["page_sentence_count_spacy"] = len(item["sentences"])

#check a random sample
#random.sample(pages_text, k=1)
pages_text[222]

#check how the data looks after spacy
df = pd.DataFrame(pages_text)
df.describe().round(2)

#max number of sentences in a chunk
sent_chunk = 15

#function ot recursively split the list
def split_list(input_list: list, slice_size: int)-> list[list[str]]:
  return [input_list[i:i+slice_size] for i in range(0, len(input_list), slice_size)]

for item in tqdm(pages_text):
  item["sentence_chunks"] = split_list(input_list=item["sentences"],
                                       slice_size = sent_chunk)
  item["num_chunks"] = len(item["sentence_chunks"])

#sample an example
random.sample(pages_text, k=1)

#get some stats about our new data
df = pd.DataFrame(pages_text)
df.describe().round(2)

"""#### Splitting each chunk into its own item"""

import re

#split each chunk into its own item
pages_chunks = []
for item in tqdm(pages_text):
  for sen_chunk in item["sentence_chunks"]:
    chunk_dict = {}
    chunk_dict["page_number"] = item["page_number"]

    #join the sentences together to form a single string
    joined_sent_chunk = "".join(sen_chunk).replace("  ", " ").strip()
    joined_sent_chunk = re.sub(r'\.([A-Z])', r'\1', joined_sent_chunk)
    chunk_dict["sen_chunk"] = joined_sent_chunk

    #some stats about the chunk
    chunk_dict["chunk_char_count"] = len(joined_sent_chunk)
    chunk_dict["chunk_word_count"] = len([word for word in joined_sent_chunk.split(" ")])
    chunk_dict["chunk_token_count"] = len(joined_sent_chunk) / 4 # 1 token = ~4 characters

    pages_chunks.append(chunk_dict)

len(pages_chunks)

random.sample(pages_chunks, k=1)

#some stats about our chunks
df = pd.DataFrame(pages_chunks)
df.describe().round(2)

# Show random chunks with under 30 tokens in length
min_token_length = 20
for row in df[df["chunk_token_count"] <= min_token_length].sample(5).iterrows():
    print(f'Chunk token count: {row[1]["chunk_token_count"]} | Text: {row[1]["sen_chunk"]}')

"""## Embedding our text chunks"""

from sentence_transformers import SentenceTransformer
embedding_model = SentenceTransformer(
    model_name_or_path = "all-mpnet-base-v2",
    device = "cpu")

#test sentences to see how the embeddings look like
sentences = [
    "The Sentences Transformers library provides an easy and open-source way to create embeddings.",
    "Sentences can be embedded one by one or as a list of strings.",
    "Embeddings are one of the most powerful concepts in machine learning!",
    "Learn to use embeddings well and you'll be well on your way to being an AI engineer."
]
#initialise an instance of the embedding model
embeddings = embedding_model.encode(sentences)
embeddings_dict = dict(zip(sentences, embeddings))

# See the embeddings
for sentence, embedding in embeddings_dict.items():
    print("Sentence:", sentence)
    print("Embedding:", embedding)
    print("")

# Commented out IPython magic to ensure Python compatibility.
# %time
#send model to the GPU
embedding_model.to("cuda")

#perform a batched operation to embed our chunks
text_chunks = [item["text"] for item in chunks_text]

# Commented out IPython magic to ensure Python compatibility.
# %time
embedding_model.to("cuda")

#create a column in our dataframe for the embeddings
for item in tqdm(chunks_text):
  item["embedding"] = embedding_model.encode(item["text"])

text_embedding = pd.DataFrame(chunks_text)
text_embedding.head()

"""### Save the embeddings to file
Easier than having to embed text all over again
"""

text_embeddings_df = pd.DataFrame(chunks_text)
embeddings_df = "/content/drive/MyDrive/DME/Build/Train/text_embeddings.csv"
text_embeddings_df.to_csv(embeddings_df, index=False)

!ls "/content/drive/MyDrive/DME/Build/Train"

text_embeddings_df["embedding"]

import pandas as pd
#import csv file to see if it has saved properly
embeddings_df = "/content/drive/MyDrive/DME/Build/Train/text_embeddings.csv"
text_chunks_test = pd.read_csv("/content/drive/MyDrive/DME/Build/Train/text_embeddings.csv")
text_chunks_test.head()

text_chunks_test.tail()

"""# Semantic search
Matching a user question with docs with similar meaning. Use the embedding created in the section above to identify similarity
"""

import random
import torch
import numpy as np
import pandas as pd
import ast

device = "cuda" if torch.cuda.is_available() else "cpu"

#import texts and embedding df
text_embeddings_df = pd.read_csv(embeddings_df)

 #convert embedding column to np array
text_embeddings_df["embedding"] = text_embeddings_df["embedding"].apply(lambda x: np.fromstring(x.strip("[]"), sep = " "))

#convert texts and embedding df to list of dicts
chunks_texts = text_embeddings_df.to_dict(orient="records")

#convert the numpy array to pytorch tensor
embeddings = torch.tensor(np.array(text_embeddings_df["embedding"].to_list()), dtype = torch.float32).to(device)
embeddings.shape

text_embeddings_df.head()

import random
import torch
import numpy as np
import pandas as pd

#path with our embeddings
embeddings_df = "/content/drive/MyDrive/DME/Build/Train/text_embeddings.csv"

device = "cuda" if torch.cuda.is_available() else "cpu"

text_embeddings_df = pd.read_csv(embeddings_df)
text_embeddings_df["embedding"].head()

text_embeddings_df.tail()

#creating an extra instance of the embedding model to avoid rerun
from sentence_transformers import util, SentenceTransformer

embedding_model = SentenceTransformer(
    model_name_or_path = "all-mpnet-base-v2",
    device = device
)

#perform a semantic search --example
#define a query string
query = "role of medicine men"
print(f"Query: {query}")

#turn the query string to an embedding with the same model
query_embedding = embedding_model.encode(
    query, convert_to_tensor = True
)

#get similarity scores (dot product)
dot_scores = util.dot_score(a = query_embedding, b = embeddings)[0]

print(f"Length of embeddings: {len(embeddings)}")
top_results_dp = torch.topk(dot_scores, k=5)
top_results_dp

#a helper function to print wrapped text
import textwrap

def print_wrapped(text, wrap_length=80):
  wrapped_text = textwrap.fill(text, wrap_length)
  print(wrapped_text)

print(f"Query: {query}\n")
print("Results:")

#loop through zipped together scores and indices from torch.topk
for score, idx in zip(top_results_dp[0], top_results_dp[1]):
  print(f"Score: {score:.7f}")
  print(f"Text: {chunks_text[idx]['text']}")
  print("\n")

"""### Functionizing our semantic search pipeline
Make the semantic search into a function that can be called. In this project, as we working with NLU techniques, text and words, we will be using cosine similarity as it measures direction unlike dot product which measures only the euclidian distance
"""

!pip install torchmetrics

import time
import torch.nn.functional as F #import pytorch function apis libraries

#a function to retrieve resources
def retrieve_resources(
    query: str, embeddings: torch.tensor,
    model: SentenceTransformer = embedding_model,
    n_resources_to_return: int=3,
    print_time: bool=False):

  #embed the query
  query_embedding = model.encode(query, convert_to_tensor=True)

  #get the cosine similarity of the embeddings
  start_time = time.time()
  cos_scores = F.cosine_similarity(query_embedding[None: ], embeddings, dim=1)
  end_time = time.time()

  if print_time:
    print(f"Time taken to get scores on {len(embeddings)} embeddings: {end_time-start_time:.5f} seconds")

  scores, indices = torch.topk(
      input = cos_scores,
      k = n_resources_to_return
  )
  return scores, indices

def print_top_results(
    query: str, embeddings: torch.tensor,
    chunks_text: list[dict] = chunks_text,
    n_resources_to_return: int = 5):
  scores, indices = retrieve_resources(
      query = query, embeddings = embeddings,
      n_resources_to_return= n_resources_to_return
  )

  print(f"Query: {query}")
  print("Results:")

  #loop through zipped together scores and indices
  for score, index in zip(scores, indices):
    print(f"Score: {score:.4f}")
    #print relevant sentence chunks
    print(f"{chunks_text[index]['text']}")
    print("\n")

#test our functions
query = "Isaiah's prophecy"

scores, indices = retrieve_resources( query=query,
                                     embeddings = embeddings)
scores, indices

#print out the text with top scores
print_top_results(
    query=query,
    embeddings = embeddings
)

"""### Get access to the LLM model (gemma)"""

!pip install huggingface_hub

from huggingface_hub import login
login()

"""#### Getting an LLM for local generation
Time for the G part of RAG
"""

#get GPU available memory (if any)
import torch
gpu_mem = torch.cuda.get_device_properties(0).total_memory
gpu_memory_gb = round(gpu_mem/(2**30))
print(f"Available mem: {gpu_memory_gb} GB")

#check with version of Gemma is best for running here
if gpu_memory_gb < 5.1:
    print(f"Your available GPU memory is {gpu_memory_gb}GB, you may not have enough memory to run a Gemma LLM locally without quantization.")
elif gpu_memory_gb < 8.1:
    print(f"GPU memory: {gpu_memory_gb} | Recommended model: Gemma 2B in 4-bit precision.")
    use_quantization_config = True
    model_id = "google/gemma-2b-it"
elif gpu_memory_gb < 19.0:
    print(f"GPU memory: {gpu_memory_gb} | Recommended model: Gemma 2B in float16 or Gemma 7B in 4-bit precision.")
    use_quantization_config = False
    model_id = "google/gemma-2b-it"
elif gpu_memory_gb > 19.0:
    print(f"GPU memory: {gpu_memory_gb} | Recommend model: Gemma 7B in 4-bit or float16 precision.")
    use_quantization_config = False
    model_id = "google/gemma-7b-it"

print(f"use_quantization_config set to: {use_quantization_config}")
print(f"model_id set to: {model_id}")

#get the LLM
#the model to be used is gemma-2b-it (instruction tuned) for generation

#import required tokens
import torch
from transformers import AutoTokenizer, AutoModelForCausalLM
from transformers.utils import is_flash_attn_2_available

#check if flash attention is available else set the attention mechanism to sdpa
if(is_flash_attn_2_available()) and (torch.cuda.get_device_capability(0)[0] >= 8):
    attn_implementation = "flash_attention_2"
else:
    attn_implementation = "sdpa" #scaled dot product attention

print(f"[INFO] Using attention implementation: {attn_implementation}")

#pick a model to use
model_id = model_id
print(f"[INFO] Using model id: {model_id}")

#instantiate tokenizer
tokenizer = AutoTokenizer.from_pretrained(pretrained_model_name_or_path = model_id)

#insatntiate the model
llm_model = AutoModelForCausalLM.from_pretrained(
    pretrained_model_name_or_path = model_id,
    torch_dtype = torch.float16,
    quantization_config = None,
    low_cpu_mem_usage = False,
    attn_implementation = attn_implementation)

llm_model.to("cuda")

#get the number of parameters
def get_params(model: torch.nn.Module):
    return sum([param.numel() for param in model.parameters()])

get_params(llm_model)

#get some info about the model
def get_model_size(model: torch.nn.Module):
    mem_params = sum([param.nelement()*param.element_size() for param in model.parameters()])
    mem_buffers = sum([buf.nelement() * buf.element_size() for buf in model.buffers()])

    # Calculate various model sizes
    model_mem_bytes = mem_params + mem_buffers # in bytes
    model_mem_mb = model_mem_bytes / (1024**2) # in megabytes
    model_mem_gb = model_mem_bytes / (1024**3) # in gigabytes

    return {"model_mem_bytes": model_mem_bytes,
            "model_mem_mb": round(model_mem_mb, 2),
            "model_mem_gb": round(model_mem_gb, 2)}

get_model_size(llm_model)

"""### Generating text with LLM"""

input_text = "What is the meaning of christian religous education?"
print(f"Input text: \n {input_text}")

#create prompt temptlate for instruction-tuned model
dialogue_template = [
    {"role": "user",
    "content": input_text}
]

#Apply the chat template
prompt = tokenizer.apply_chat_template(
    conversation = dialogue_template,
    tokenize = False,
    add_generation_prompt = True)

print(f"\nPrompt (formatted):\n {prompt}")

#tokenize the input text and send it to GPU
input_ids = tokenizer(
    prompt, return_tensors="pt").to("cuda")
print(f"Model input (tokenized):\n {input_ids}\n")

#generate outputs passed on the tokenized input
outputs = llm_model.generate(
    **input_ids, max_new_tokens = 256)
print(f"Model output (tokens):\n{outputs[0]}n")

outputs_decoded = tokenizer.decode(outputs[0])
print(f"Model output (decoded):\n{outputs_decoded}\n")

print(f"Input text: {input_text}\n")
print(f"Output text:\n{outputs_decoded.replace(prompt, '').replace('<bos>','').replace('<eos>', '')}")

"""### Response augumentation
Augumented answer for a response
"""

#create questions for testing
questions = [
    "Outline six categories of prophets",
    "Outine reasons why the bible is referred to as the word of God",
    "Give reasons for naming children"
]

#check if our retrieve function works with our list of queries
import random
query = random.choice(questions)

print(f"Query: {query}")

#get just scores and indices of top related results
scores, indices = retrieve_resources(query = query,
                                    embeddings = embeddings)
scores, indices

"""#### Augumenting our prompt with context items"""

def prompt_formatter(query: str,
                    context_items: list[dict]) -> str:
    #join contnext items into one dotted paragraph
    context = "- " + "\n- ".join([item["text"] for item in context_items])

    ###create a base prompt with examples to help the model
    base_prompt = """
    Based on the following context items, please answer the query.
Give yourself room to think by extract relevant passages from the context before answering the query.
Don't return the thinking, only return the answer.
Return the answer as points. Make sure the answers are exhaustive while still accurate
Use the following examples as references for the ideal answer style.
\nExample 1:
Query: Give seven responsibilities of the living towards ancestors in African Traditional Communities
Answer:
Naming children after them
Pouring libation for them
Taking care of their graveyards
Making sacrifices to honour them
Consulting/ communicating to them in times of need
Inviting/ involving them in ceremonies
Invoking their names during prayers
Transmitting their wishes/visions
By holding commermoration ceremonies for them
Managing their property wisely
Building shrines for them
Teaching children about them
\nExample 2:
Query: Give seven similarities between Jewish and traditional African practice of circumcision
Answer:
In both cases, it promotes one into full membership of the community.
In both cases, it is a mark of identification of a person to a particular community.
In both cases, it is carried out on male children.
In both cases, circumcision has a religious significance.
In both cases, special people/ religious leaders/heads of the community carry out the operations.
In both cases, it unites the members with the ancestors.
In both cases, members receive new names.
In both cases, the rite is carried on from generation to generation/ is compulsory/ whoever fails to observe it is considered an outcast.
In both cases, the ritual is a communal affair.
In both cases, it involves the cutting of the foreskin.
\nExample 3:
Query: State six similarites between the First and the second account of creation
Answer:
In both, God is portrayed as the only sole creator.
In both, man is portrayed as 2 special creatures; man was created in God's image and likeness, and there was nothing else created in that way
Both outline the creation of the living and non-living things, i.e., heaven, man, plants, and animals, etc.
In both, Man shares in with God. God breathed e!l into man' s nostrils and created him in his own image.
In both cases, Man is given special privileges and responsibilities, and is to multiply and fill the earth.
In both stories, God existed before creation.
In both mankind is created into full sexuality (male and female).
\nNow use the following context items to answer the user query:
{context}
\nRelevant passages: <extract relevant passages from the context here>
User query: {query}
Answer:
"""
    #update the base prompt with the context items and query
    base_prompt = base_prompt.format(context=context, query=query)

    #create a prompt template for the instruction-tuned model
    dialogue_template = [
        {"role":"user",
        "content":base_prompt}]

    #apply the chat template
    prompt = tokenizer.apply_chat_template(conversation=dialogue_template,
                                          tokenize=False,
                                          add_generation_prompt=True)

    return prompt

query = random.choice(questions)
print(f"Query: {query}")

#get relevant resources
scores, indices = retrieve_resources(query=query,
                                    embeddings = embeddings)

#create a list of context items
context_items = [chunks_text[i] for i in indices]

#format prompt with context items
prompt = prompt_formatter(query=query,
                         context_items = context_items)

print(prompt)

#tokenize and pass it straight to our LLM
input_ids = tokenizer(prompt, return_tensors="pt").to("cuda")

#generate an output of tokens
outputs = llm_model.generate(**input_ids,
                            temperature = 0.6,
                            do_sample=True,
                            max_new_tokens=256)

#turn the output tokens into human readable text
output_text = tokenizer.decode(outputs[0])

print(f"Query: {query}")
print(f"RAG answer: \n {output_text.replace(prompt, '').replace('<bos>', '').replace('<eos>', '')}")

def ask(query,
       temperature = 0.4,
       max_new_tokens = 512,
       format_answer_text=True,
       return_answer_only = False):

    #get just the scores and indices of the top related results
    scores, indices = retrieve_resources(query = query,
                                        embeddings = embeddings)

    #create a list of context items
    context_items = [chunks_text[i] for i in indices]

    #add score to context item
    for i, item in enumerate(context_items):
        item["score"] = scores[i].cpu()

    #format the prompt with context items
    prompt = prompt_formatter(query=query,
                             context_items=context_items)

    #tokenize the prompt
    input_ids = tokenizer(prompt, return_tensors="pt").to("cuda")

    #generate an output of tokens
    outputs = llm_model.generate(**input_ids,
                          temperature = temperature,
                          do_sample = True,
                          max_new_tokens=max_new_tokens)

    #turn the output to human readable text
    output_text = tokenizer.decode(outputs[0])

    if format_answer_text:
        output_text = output_text.replace(prompt, '').replace('<bos>', '').replace('<eos>', '').replace("Sure, here is the answer to the user query:\n\n", "")

    #only return the answer without the context items
    if return_answer_only:
        return output_text

    return output_text, context_items

#test the function
query = "Outline categories of true prophets"
print(f"Question: {query}")

#answer query with context
answer= ask(query=query,
            temperature=0.4,
            max_new_tokens = 512,
            format_answer_text=True,
            return_answer_only = True)

print(f"Answer:\n{answer}")
print("\n\n\n\n")
#print(f"Context items: {context_items}")

print("Exam setting")
query = input("Enter a question:")
print(f"Question: {query}")


#answer query with context
answer, context_items = ask(query=query,
            temperature=0.1,
            max_new_tokens = 1024,
            format_answer_text=True,
            return_answer_only = False)

print(f"Answer:\n{answer}")
print("\n\n\n\n")
#print(f"Context items: {context_items}")