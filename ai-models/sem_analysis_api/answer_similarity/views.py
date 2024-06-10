import logging

logger = logging.getLogger(__name__)

from django.shortcuts import render

# Create your views here.
# answer_similarity/views.py
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from .serializers import PassageSerializer
from django.http import JsonResponse
from .utils import analyse_answers



class AnalyseAnswersView(APIView):
    def post(self, request, *args, **kwargs):
        logger.debug(f"{request.data}") 
        serializer = PassageSerializer(data=request.data)

        if serializer.is_valid():
            answer_scheme = serializer.validated_data['answer_scheme']
            student_answer = serializer.validated_data['student_answer']
            result = analyse_answers(answer_scheme, student_answer)
            if result is not None:
                return Response(result, status=status.HTTP_200_OK)
            else:
                return Response({"error": "Error in analyse_answers function"}, status=status.HTTP_500_INTERNAL_SERVER_ERROR)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
