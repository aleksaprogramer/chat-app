"use strict";


// DOM elements
const username = document.getElementById(`username`);
const message = document.getElementById(`message`);
const sendBtn = document.getElementById(`send-btn`);
const messagesOutput = document.getElementById(`messages-output`);

// Connection with WebSocket
const socket = new WebSocket(`ws://localhost:8080`);

socket.addEventListener(`open`, () => {
    console.log(`Connected on server`);

    sendBtn.addEventListener(`click`, e => {
        e.preventDefault();

        socket.send(`${username.value}: ${message.value}`);

        message.value = ``;
    })
})

socket.addEventListener(`message`, e => {

    const html = `<p>${e.data}</p>`;

    messagesOutput.insertAdjacentHTML(`afterbegin`, html);
})