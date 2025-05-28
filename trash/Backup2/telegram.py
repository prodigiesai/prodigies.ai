from telethon.sync import TelegramClient
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
from datetime import datetime, timedelta, timezone

# Configuración de la API de Telegram
api_id = "27327034"  # Reemplázalo con tu API ID
api_hash = "71243d28d8f9f1f424700ee6e5bb8dc6"  # Reemplázalo con tu API Hash

# Inicializar cliente de Telegram
client = TelegramClient("session_name", api_id, api_hash)

# Inicializar el analizador VADER
analyzer = SentimentIntensityAnalyzer()

def analizar_sentimiento_ultimas_24h(canales, limit=200):
    """
    Analiza el sentimiento de los mensajes recibidos en las últimas 24 horas
    relacionados con "Bitcoin" o "BTC" en múltiples canales.
    Retorna el porcentaje combinado de mensajes positivos.
    """
    try:
        mensajes_relevantes = []
        ahora = datetime.now(timezone.utc)
        hace_24h = ahora - timedelta(hours=24)

        # Conectar al cliente
        client.start()

        # print(f"\n--- Analizando mensajes de los últimos 24 horas en los canales: {', '.join(canales)} ---\n")

        for canal in canales:
            # print(f"\n--- Analizando canal: {canal} ---\n")
            # Obtener mensajes recientes del canal
            messages = client.get_messages(canal, limit=limit)

            # Filtrar mensajes por las últimas 24 horas y contenido relevante
            for message in messages:
                if (
                    message.text
                    and ("bitcoin" in message.text.lower() or "btc" in message.text.lower())
                    and message.date >= hace_24h
                ):
                    sentiment = analyzer.polarity_scores(message.text)
                    mensajes_relevantes.append({
                        "texto": message.text,
                        "fecha": message.date,
                        "sentimiento": sentiment["compound"]
                    })

        # Si no hay mensajes relevantes, mostrar aviso
        if not mensajes_relevantes:
            print("No se encontraron mensajes relacionados con Bitcoin o BTC en las últimas 24 horas.")
            return 0

        # Calcular el porcentaje de mensajes positivos
        positivos = sum(1 for m in mensajes_relevantes if m["sentimiento"] > 0)
        porcentaje_positivo = (positivos / len(mensajes_relevantes)) * 100

        # # Ordenar por fecha de más reciente a más antiguo
        # mensajes_relevantes.sort(key=lambda x: x["fecha"], reverse=True)

        # # Mostrar resultados
        # for mensaje in mensajes_relevantes:
        #     print(f"Fecha: {mensaje['fecha']}")
        #     print(f"Mensaje: {mensaje['texto']}")
        #     print(f"Sentimiento: {mensaje['sentimiento']}\n")

        return porcentaje_positivo

    except Exception as e:
        print(f"Error al analizar: {e}")
        return None
    finally:
        client.disconnect()

# Ejecutar análisis
if __name__ == "__main__":
    canales = ["cointrendz", "www_Bitcoin_com", "icodrops"]  # Lista de canales
    limite_mensajes = 200  # Número máximo de mensajes a analizar por canal

    # print(f"Analizando mensajes en los canales: {', '.join(canales)} (últimas 24 horas)...")
    porcentaje_positivo = analizar_sentimiento_ultimas_24h(canales, limite_mensajes)

    if porcentaje_positivo is not None:
        print(f"\nPorcentaje combinado de sentimiento positivo: {porcentaje_positivo:.2f}%")
    else:
        print("No se pudo realizar el análisis.")