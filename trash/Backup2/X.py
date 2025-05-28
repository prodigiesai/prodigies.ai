from textblob import TextBlob
import tweepy

def analizar_sentimiento_simple(query, horas=1, max_tweets=100):
    # Configuración de la API de X
    API_KEY = 'TU_API_KEY'
    API_SECRET = 'TU_API_SECRET'
    ACCESS_TOKEN = 'TU_ACCESS_TOKEN'
    ACCESS_TOKEN_SECRET = 'TU_ACCESS_TOKEN_SECRET'

    # Autenticación
    auth = tweepy.OAuthHandler(API_KEY, API_SECRET)
    auth.set_access_token(ACCESS_TOKEN, ACCESS_TOKEN_SECRET)
    api = tweepy.API(auth, wait_on_rate_limit=True)
    
    # Obtener tweets recientes
    tweets = tweepy.Cursor(api.search_tweets, q=query, lang="en").items(max_tweets)
    tweets_text = [tweet.text for tweet in tweets]

    # Contar polaridades
    positivos = 0
    total = 0

    for tweet in tweets_text:
        analisis = TextBlob(tweet)
        if analisis.sentiment.polarity > 0:
            positivos += 1
        total += 1

    # Calcular porcentaje de aceptación positiva
    if total == 0:
        return 0  # Evita división por cero si no hay tweets
    return (positivos / total) * 100