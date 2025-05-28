import praw
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
from datetime import datetime, timezone, timedelta

# Configuración de la API de Reddit
reddit = praw.Reddit(
    client_id="tumzml3to0x6SpppuEfrQQ",       # Cambia esto
    client_secret="lPHYYTWNVi85J6KtuglDD9AmIHSoPA",  # Cambia esto
    user_agent="analisis-btc-sentimiento"
)

# Inicializar el analizador VADER
analyzer = SentimentIntensityAnalyzer()

def analizar_sentimiento_vader(subreddit_name="Bitcoin", max_posts=50, horas=6):
    """
    Realiza análisis de sentimiento avanzado usando VADER en posts recientes de Reddit.
    Filtra publicaciones de las últimas 'horas' horas.
    Retorna el porcentaje de posts positivos.
    """
    try:
        # Obtener posts recientes del subreddit
        subreddit = reddit.subreddit(subreddit_name)
        posts = subreddit.new(limit=max_posts)

        positivos = 0
        total_posts = 0

        # Tiempo actual y límite de tiempo
        ahora = datetime.now(timezone.utc)
        limite_tiempo = ahora - timedelta(hours=horas)

        for post in posts:
            # Convertir timestamp de creación del post a datetime
            fecha_post = datetime.fromtimestamp(post.created_utc, tz=timezone.utc)

            # Filtrar posts dentro del rango de tiempo
            if fecha_post >= limite_tiempo:
                # Analizar el sentimiento del título del post
                sentiment = analyzer.polarity_scores(post.title)
                if sentiment['compound'] > 0:  # Sentimiento positivo
                    positivos += 1
                total_posts += 1

        # Evitar división por cero
        if total_posts == 0:
            return 0

        # Porcentaje de posts positivos
        return (positivos / total_posts) * 100
    except Exception as e:
        print(f"Error al analizar: {e}")
        return None


def visualizar_sentimiento_vader(subreddit_name="Bitcoin", max_posts=50, horas=6):
    """
    Realiza análisis de sentimiento avanzado usando VADER en posts recientes de Reddit.
    Filtra publicaciones de las últimas 'horas' horas.
    Retorna el porcentaje de posts positivos.
    """
    try:
        # Obtener posts recientes del subreddit
        subreddit = reddit.subreddit(subreddit_name)
        posts = subreddit.new(limit=max_posts)

        positivos = 0
        total_posts = 0

        # Tiempo actual y límite de tiempo
        ahora = datetime.now(timezone.utc)
        limite_tiempo = ahora - timedelta(hours=horas)

        print(f"\n--- Posts considerados (últimas {horas} horas) ---\n")

        for post in posts:
            # Convertir timestamp de creación del post a datetime
            fecha_post = datetime.fromtimestamp(post.created_utc, tz=timezone.utc)

            # Filtrar posts dentro del rango de tiempo
            if fecha_post >= limite_tiempo:
                print(f"Post: {post.title}")
                print(f"Fecha: {fecha_post.strftime('%Y-%m-%d %H:%M:%S')} UTC\n")

                # Analizar el sentimiento del título del post
                sentiment = analyzer.polarity_scores(post.title)
                if sentiment['compound'] > 0:  # Sentimiento positivo
                    positivos += 1
                total_posts += 1

        # Evitar división por cero
        if total_posts == 0:
            return 0

        # Porcentaje de posts positivos
        return (positivos / total_posts) * 100
    except Exception as e:
        print(f"Error al analizar: {e}")
        return None
    

# Ejecutar análisis
if __name__ == "__main__":
    subreddit_name = "Bitcoin"  # Cambia si deseas otro subreddit
    max_posts = 100  # Número máximo de posts a analizar
    horas = 6  # Cambia para ajustar el rango de tiempo

    print(f"Analizando sentimiento en r/{subreddit_name} (últimas {horas} horas)...")
    porcentaje_positivo = visualizar_sentimiento_vader(subreddit_name, max_posts, horas)

    if porcentaje_positivo is not None:
        print(f"\nPorcentaje de sentimiento positivo: {porcentaje_positivo:.2f}%")
    else:
        print("No se pudo realizar el análisis.")