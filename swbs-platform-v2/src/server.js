require('dotenv').config();
const http = require('http');
const path = require('path');
const { Server } = require('socket.io');
const app = require('./app');
const { initSocketIo } = require('./services/chatService');

const PORT = process.env.PORT || 3000;

const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: process.env.DOMAIN || '*',
    methods: ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'],
  },
});

initSocketIo(io);

server.listen(PORT, () => {
  console.log(`SWBS-PLATEFORME-V2 server listening on port ${PORT}`);
  console.log(`Environment: ${process.env.NODE_ENV || 'development'}`);
  console.log(`Domain: ${process.env.DOMAIN || 'http://localhost:' + PORT}`);
});