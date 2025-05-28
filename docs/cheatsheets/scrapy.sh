# Instalación de Scrapy
pip install scrapy

# Crear un Proyecto Scrapy
scrapy startproject my_scraper

# Crear un Spider
scrapy genspider my_spider example.com


# my_scraper/spiders/my_spider.py

import scrapy

class MySpider(scrapy.Spider):
    name = 'my_spider'
    start_urls = ['https://example.com']  # URL inicial donde comienza el scraping

    def parse(self, response):
        # Extraer información específica con selectores CSS o XPath

        # Buscar nombres (en este caso usando etiquetas h2 como ejemplo)
        names = response.css('h2::text').getall()
        yield {'names': names}

        # Buscar correos electrónicos
        emails = response.css('a[href^="mailto"]::attr(href)').re(r'mailto:(.*)')
        yield {'emails': emails}

        # Buscar números de teléfono (simple regex para encontrar patrones)
        phones = response.xpath("//*[contains(text(), 'Phone') or contains(text(), 'Tel')]//text()").re(r'\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}')
        yield {'phones': phones}

        # Buscar puestos de trabajo (usando posibles clases o IDs de HTML)
        job_titles = response.css('div.job-title::text').getall()
        yield {'job_titles': job_titles}

        # Buscar direcciones (posible regex o selectores de dirección)
        addresses = response.css('div.address::text').re(r'\d{1,5} \w+ (Street|Avenue|Boulevard|Blvd|Road|Rd)')
        yield {'addresses': addresses}

        # Navegar a la siguiente página (si hay paginación)
        next_page = response.css('a.next::attr(href)').get()
        if next_page is not None:
            yield response.follow(next_page, self.parse)  # Seguir a la siguiente página



# Ejecutar el Spider
scrapy crawl my_spider -o data.json

# Manejo de Paginación Automática response.follow()








# Manejo de Redes Sociales y Protección Anti-Scraping
# Para scraping de redes sociales (como Twitter, LinkedIn, etc.), muchas plataformas tienen políticas anti-scraping o usan CAPTCHAs y autenticación. Scrapy por sí solo no maneja JavaScript, pero puedes combinarlo con herramientas como Selenium si necesitas cargar páginas dinámicas o resolver CAPTCHAs.
# Si el sitio tiene protección contra bots, Scrapy permite la rotación de user agents, el manejo de cookies, e integración con servicios de proxies para evitar bloqueos:

# En settings.py del proyecto Scrapy, puedes agregar rotación de User-Agents y Proxies

# Rotar User-Agents
USER_AGENT_LIST = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3",
    "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0",
    # Agrega más user-agents para rotación
]

DOWNLOADER_MIDDLEWARES = {
    'scrapy.downloadermiddlewares.useragent.UserAgentMiddleware': None,
    'scrapy_user_agents.middlewares.RandomUserAgentMiddleware': 400,
}
