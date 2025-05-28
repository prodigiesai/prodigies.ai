import feedparser
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
from datetime import datetime, timedelta
from bs4 import BeautifulSoup

# Inicializar el analizador VADER
analyzer = SentimentIntensityAnalyzer()

def limpiar_html(texto):
    """
    Elimina las etiquetas HTML de un texto utilizando BeautifulSoup.
    """
    soup = BeautifulSoup(texto, "html.parser")
    return soup.get_text()

def analizar_sentimiento_rss(feeds, max_items=50, palabras_clave=None):
    """
    Analiza el sentimiento de las noticias en múltiples feeds RSS que cumplan ciertos criterios:
    - Contengan palabras clave en el título o contenido.
    - Sean de las últimas 24 horas.
    Retorna el porcentaje de noticias con sentimiento positivo.
    """
    try:
        positivos = 0
        total_items = 0
        ahora = datetime.utcnow()
        hace_24_horas = ahora - timedelta(hours=24)

        # print("\n--- Noticias analizadas ---\n")
        for feed_url in feeds:
            # print(f"\nAnalizando feed: {feed_url}\n")
            feed = feedparser.parse(feed_url)
            entries = feed.entries[:max_items]

            if not entries:
                # print(f"No se encontraron noticias en el feed: {feed_url}")
                continue

            for entry in entries:
                # Filtrar por fecha (últimas 24 horas)
                if 'published_parsed' in entry:
                    fecha_noticia = datetime(*entry.published_parsed[:6])
                    if fecha_noticia < hace_24_horas:
                        continue

                # Buscar palabras clave en el título o contenido
                title = entry.title
                # content = limpiar_html(entry.get('summary', ''))  # Limpiar contenido HTML
                content = entry.get('summary', '')
                if palabras_clave and not any(keyword.lower() in (title + content).lower() for keyword in palabras_clave):
                    continue

                # Analizar sentimiento
                texto_analizar = title + " " + content
                sentiment = analyzer.polarity_scores(texto_analizar)

                # Ponderar noticias más recientes
                horas_desde_publicacion = (ahora - fecha_noticia).total_seconds() / 3600
                peso = max(0, 1 - (horas_desde_publicacion / 24))  # Peso decae linealmente hasta 24 horas

                # print(f"Fecha: {fecha_noticia}")
                # print(f"Noticia: {title}")
                # print(f"Contenido: {content}")
                # print(f"Sentimiento: {sentiment}")
                # print(f"Peso asignado: {peso:.2f}\n")

                if sentiment['compound'] > 0:  # Sentimiento positivo
                    positivos += peso
                total_items += peso

        # Calcular porcentaje de sentimiento positivo
        return (positivos / total_items) * 100 if total_items > 0 else 0
    except Exception as e:
        print(f"Error al analizar: {e}")
        return None

# Ejecutar análisis
if __name__ == "__main__":
    feeds = [
        "https://cointelegraph.com/rss/tag/bitcoin",
        "https://www.coindesk.com/arc/outboundfeeds/rss",
        "https://blog.bitcoin.com/feed",
        "https://bitcoinmagazine.com/.rss/full/"
    ]
    max_items = 20  # Número máximo de noticias a analizar por feed
    palabras_clave = ["BTC", "Bitcoin"]  # Palabras clave para filtrar noticias

    # print("Analizando sentimiento de múltiples feeds RSS.")
    porcentaje_positivo = analizar_sentimiento_rss(feeds, max_items, palabras_clave)

    if porcentaje_positivo is not None:
        print(f"\nPorcentaje de sentimiento positivo: {porcentaje_positivo:.2f}%")
    else:
        print("No se pudo realizar el análisis.")