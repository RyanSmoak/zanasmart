# answer_similarity/urls.py
from django.urls import path
from .views import AnalyseAnswersView

urlpatterns = [
    path('analyse/', AnalyseAnswersView.as_view(), name='analyse_answers'),
]
