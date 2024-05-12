import Echo from 'laravel-echo';

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'admin.movel.id' // replace with your Node.js server's host and port
});