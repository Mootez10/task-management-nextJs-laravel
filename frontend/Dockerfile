FROM node:20-alpine


WORKDIR /app


COPY package*.json ./


RUN npm install


COPY . .


RUN chmod -R 777 /app


EXPOSE 3000


ENV PORT 3000


CMD ["npm", "run", "dev"]

