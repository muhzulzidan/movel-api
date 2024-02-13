const io = require('socket.io')(3000);
const redisAdapter = require('socket.io-redis');

io.adapter(redisAdapter({ host: 'localhost', port: 6379 }));

io.on('connection', (socket) => {
  console.log('a user connected');
});
