FROM node:18.14.2-alpine3.17

# Nastavenie pracovného adresára
WORKDIR /usr/src/frontend

# Kopírovanie package.json a inštalácia závislostí
COPY ./frontend/package*.json ./
RUN npm install

# Kopírovanie zdrojových súborov
COPY ./frontend ./

# Exponovanie portu
EXPOSE 3000

# Spustenie vývojového servera
CMD ["npm", "run", "dev"]
