# answer_similarity/serializers.py
from rest_framework import serializers

class PassageSerializer(serializers.Serializer):
    answer_scheme = serializers.CharField()
    student_answer = serializers.CharField()
