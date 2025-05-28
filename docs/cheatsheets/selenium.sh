# Pasos para Usar Proxies Rotativos en Selenium:
# Adquirir un servicio de proxies rotativos: Existen muchos servicios que proporcionan proxies rotativos, como:
# Bright Data # ProxyMesh # ScraperAPI # Smartproxy

pip install selenium

# 1. Uso Básico de un Proxy en Selenium:


from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service

# Configuración del proxy
PROXY = "ip:puerto"  # Reemplaza con tu IP y puerto del proxy

chrome_options = Options()
chrome_options.add_argument(f'--proxy-server={PROXY}')  # Configura el proxy

webdriver_service = Service('path/to/chromedriver')  # Ruta a ChromeDriver
driver = webdriver.Chrome(service=webdriver_service, options=chrome_options)

# Abre LinkedIn con el proxy configurado
driver.get("https://www.linkedin.com")




# 2. Rotación de Proxies (con Lista de Proxies):

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
import random

# Lista de proxies (ejemplo de lista con IPs y puertos)
proxies = [
    '123.45.67.89:8080',
    '98.76.54.32:3128',
    '12.34.56.78:9090',
    # Agrega más proxies según tus necesidades
]

# Función para elegir un proxy aleatorio de la lista
def get_random_proxy():
    return random.choice(proxies)

# Función para configurar Selenium con un proxy
def setup_selenium_with_proxy(proxy):
    chrome_options = Options()
    chrome_options.add_argument(f'--proxy-server={proxy}')
    webdriver_service = Service('path/to/chromedriver')
    return webdriver.Chrome(service=webdriver_service, options=chrome_options)

# Obtener un proxy aleatorio y configurar Selenium
proxy = get_random_proxy()
driver = setup_selenium_with_proxy(proxy)

# Navegar a LinkedIn con el proxy seleccionado
driver.get("https://www.linkedin.com")

# Realizar scraping o cualquier otra acción




# 3. Usar un Servicio de Proxies Rotativos (como ScraperAPI o Bright Data):

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service

# Usar un servicio de proxies rotativos (ScraperAPI en este caso)
SCRAPER_API_URL = "http://proxy.scraperapi.com?api_key=YOUR_API_KEY&url="

chrome_options = Options()
chrome_options.add_argument(f'--proxy-server={SCRAPER_API_URL}')
webdriver_service = Service('path/to/chromedriver')
driver = webdriver.Chrome(service=webdriver_service, options=chrome_options)

# Navegar a LinkedIn o cualquier otra página
driver.get("https://www.linkedin.com")



# Manejo de Tiempos de Espera (Delays): Es recomendable agregar delays (esperas) entre solicitudes para evitar que el servidor detecte un comportamiento de bot. Puedes usar time.sleep() entre solicitudes:
import time
time.sleep(random.uniform(2, 5))  # Espera entre 2 y 5 segundos aleatoriamente
