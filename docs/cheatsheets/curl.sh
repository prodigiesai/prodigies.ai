# cURL Cheat Sheet

# -----------------------------
# 1. Basic HTTP Requests
# -----------------------------

curl http://example.com  # Perform a GET request to a URL
curl -X POST http://example.com  # Perform a POST request to a URL
curl -I http://example.com  # Fetch only the HTTP headers with a HEAD request

# -----------------------------
# 2. Downloading Files
# -----------------------------

curl -O http://example.com/file.txt  # Download a file (keep original file name)
curl -o newfile.txt http://example.com/file.txt  # Download a file and rename it
curl -L http://example.com  # Follow redirects during the download
curl -C - -O http://example.com/file.txt  # Resume a previously interrupted download

# -----------------------------
# 3. Sending Data (POST Requests)
# -----------------------------

curl -X POST -d "param1=value1&param2=value2" http://example.com  # Send form data via POST
curl -X POST -H "Content-Type: application/json" -d '{"key":"value"}' http://example.com  # Send JSON data via POST

# Send data from a file
curl -X POST --data-binary @data.json http://example.com  # Send file content as POST data

# -----------------------------
# 4. HTTP Headers
# -----------------------------

curl -H "Authorization: Bearer <token>" http://example.com  # Send a custom HTTP header (e.g., Authorization)
curl -H "Content-Type: application/json" http://example.com  # Specify the Content-Type header
curl -H "User-Agent: Mozilla/5.0" http://example.com  # Set a custom User-Agent string

# -----------------------------
# 5. Authentication
# -----------------------------

curl -u username:password http://example.com  # HTTP Basic Authentication
curl -u username:password -X POST http://example.com  # Send credentials with POST request
curl --digest -u username:password http://example.com  # HTTP Digest Authentication

# -----------------------------
# 6. Saving Output
# -----------------------------

curl http://example.com -o output.html  # Save the output to a file
curl http://example.com -O  # Save the file with its original name from the URL
curl http://example.com -o /dev/null  # Discard the output (don't save to a file)
curl http://example.com -o - | grep "search_term"  # Pipe the output of curl to another command

# -----------------------------
# 7. Query Parameters
# -----------------------------

curl "http://example.com?param1=value1&param2=value2"  # Send query parameters in a GET request
curl -G -d "param1=value1" -d "param2=value2" http://example.com  # Send query parameters using `-G` flag

# -----------------------------
# 8. File Upload
# -----------------------------

curl -F "file=@path/to/file" http://example.com/upload  # Upload a file using multipart/form-data
curl -F "file=@path/to/file" -F "submit=Submit" http://example.com/upload  # Upload file with form field

# -----------------------------
# 9. Cookies
# -----------------------------

curl -b "name=value" http://example.com  # Send a cookie with the request
curl -b cookies.txt http://example.com  # Send cookies from a file
curl -c cookies.txt http://example.com  # Save cookies to a file after a request
curl -b cookies.txt -c newcookies.txt http://example.com  # Send cookies from one file and save to another

# -----------------------------
# 10. Follow Redirects
# -----------------------------

curl -L http://example.com  # Follow redirects automatically
curl -L --max-redirs 5 http://example.com  # Limit the number of redirects to 5

# -----------------------------
# 11. Handling Errors
# -----------------------------

curl -f http://example.com  # Fail silently on server errors (4xx or 5xx)
curl --retry 5 http://example.com  # Retry the request 5 times if it fails
curl --retry 5 --retry-delay 10 http://example.com  # Retry the request 5 times with a 10-second delay

# -----------------------------
# 12. Timeouts and Limits
# -----------------------------

curl --connect-timeout 10 http://example.com  # Set connection timeout to 10 seconds
curl --max-time 30 http://example.com  # Set maximum time allowed for the entire request to 30 seconds

# -----------------------------
# 13. Output Formatting
# -----------------------------

curl -s http://example.com  # Silent mode (no progress output)
curl -v http://example.com  # Verbose mode (detailed output of the request and response)
curl -i http://example.com  # Include the HTTP response headers in the output
curl -s -o /dev/null -w "%{http_code}\n" http://example.com  # Output only the HTTP status code

# -----------------------------
# 14. Debugging Requests
# -----------------------------

curl -v http://example.com  # Verbose mode (show detailed information about the request)
curl -trace output.txt http://example.com  # Save a trace of the entire request to a file
curl -v -X GET http://example.com  # Verbose mode for a GET request

# -----------------------------
# 15. HTTPS and SSL Options
# -----------------------------

curl -k https://example.com  # Ignore SSL certificate errors
curl --cert /path/to/cert.pem --key /path/to/key.pem https://example.com  # Use a specific client certificate
curl --cacert /path/to/ca.crt https://example.com  # Use a specific Certificate Authority (CA) certificate

# -----------------------------
# 16. Proxy Settings
# -----------------------------

curl -x http://proxy.example.com:8080 http://example.com  # Use an HTTP proxy for the request
curl -x socks5://proxy.example.com:1080 http://example.com  # Use a SOCKS5 proxy for the request
curl --proxy-user user:password -x http://proxy.example.com:8080 http://example.com  # Use proxy with authentication

# -----------------------------
# 17. Download Multiple Files
# -----------------------------

curl -O http://example.com/file1.txt -O http://example.com/file2.txt  # Download multiple files in one command

# -----------------------------
# 18. Rate Limiting
# -----------------------------

curl --limit-rate 100k http://example.com/file.zip  # Limit download speed to 100 kilobytes per second

# -----------------------------
# 19. FTP Commands
# -----------------------------

curl ftp://ftp.example.com/file.txt  # Download a file via FTP
curl -T file.txt ftp://ftp.example.com/  # Upload a file via FTP
curl -u username:password ftp://ftp.example.com/file.txt  # FTP with user authentication

# -----------------------------
# 20. HTTP Methods
# -----------------------------

curl -X GET http://example.com  # Explicitly use GET method (default)
curl -X POST http://example.com  # Use POST method
curl -X PUT http://example.com  # Use PUT method
curl -X DELETE http://example.com  # Use DELETE method
curl -X PATCH http://example.com  # Use PATCH method

# -----------------------------
# 21. JSON Requests and Responses
# -----------------------------

curl -H "Content-Type: application/json" -d '{"key":"value"}' http://example.com  # Send a JSON POST request
curl -H "Accept: application/json" http://example.com  # Send GET request expecting JSON response



# Descarga la página web
curl -s https://example.com > webpage.html


# Encuentra todos los correos electrónicos en la página
curl -s https://example.com | grep -E -o "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}"


# Buscar varias palabras clave en el contenido de la página
curl -s https://example.com | grep -i -E "palabra1|palabra2|palabra3"


# Buscar divs con la clase "content" en la página web
curl -s https://example.com | grep -oP '(?<=<div class="content").*?(?=>)'


# Hacer un loop sobre varias páginas de un foro o un sitio web
for i in {1..10}; do
    curl -s "https://example.com/page/$i" > "page_$i.html"
    grep -E -o "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}" page_$i.html
done


# Hacer un request a una API y procesar el JSON
curl -s "https://api.example.com/data" | jq '.items[] | {name: .name, email: .email}'


# Enviar un request POST con credenciales de login
curl -X POST -d "username=your_username&password=your_password" https://example.com/login -c cookies.txt


# Acceder a una página autenticada usando las cookies
curl -b cookies.txt https://example.com/restricted-page


#!/bin/bash

# URL del sitio web que queremos scrappear
URL="https://example.com"

# Descargar la página web
curl -s $URL > webpage.html





# Limpiar el archivo temporal
rm webpage.html




#!/bin/bash

# URL del sitio web que queremos scrappear
URL="https://example.com"

# Descargar la página web
curl -s $URL > webpage.html

# Buscar correos electrónicos
echo "Buscando correos electrónicos..."
grep -E -o "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}" webpage.html

# Buscar números de teléfono (formatos comunes) // (555) 123-4567 o 555-1234
echo "Buscando números de teléfono..."
grep -E -o "\(?\d{2,3}\)?[-.\s]?\d{3}[-.\s]?\d{4,6}" webpage.html

# Buscar nombres (formato sencillo basado en dos palabras con mayúsculas al inicio)
echo "Buscando nombres..."
grep -E -o "\b[A-Z][a-z]+ [A-Z][a-z]+" webpage.html

# Buscar puestos de trabajo (keywords comunes como CEO, Manager, etc.)
echo "Buscando puestos de trabajo..."
grep -i -E "CEO|Manager|Engineer|Developer|Director|Analyst|Consultant" webpage.html

# Buscar direcciones (formato sencillo de calle)
echo "Buscando direcciones..."
grep -E -o "[0-9]{1,5} [A-Za-z0-9 ]+(Street|St|Avenue|Ave|Boulevard|Blvd|Road|Rd|Lane|Ln)" webpage.html

# Buscar palabras clave específicas
echo "Buscando palabras clave: 'contacto', 'soporte', 'help'..."
grep -i -E "contacto|soporte|help" webpage.html

# Buscar divs con clase específica
echo "Buscando divs con la clase 'content'..."
grep -oP '(?<=<div class="content").*?(?=>)' webpage.html

# Buscar correos electrónicos en la página web
echo "Buscando correos electrónicos..."
grep -E -o "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}" webpage.html

# Limpiar el archivo temporal
rm webpage.html



chmod +x scraper.sh
./scraper.sh
