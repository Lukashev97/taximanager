# build image
FROM node:18-alpine as BUILD_IMAGE

WORKDIR /app/frontend

COPY package.json . 

RUN npm install 

COPY . .

RUN npm run build 

# run image
FROM node:18-alpine as PRODUCTION_IMAGE

WORKDIR /app/frontend

COPY --from=BUILD_IMAGE /app/frontend/dist/ /app/frontend/dist/

EXPOSE 8080

COPY package.json .
COPY vite.config.js .

RUN npm install vite

EXPOSE 8080
CMD ["npm", "run", "preview"]

# docker build -t frontend:latest . 
# docker run -p 8080:8080 frontend:latest

