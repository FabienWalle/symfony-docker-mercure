import {Tooltip} from "bootstrap";

export class Message extends HTMLElement {
  constructor() {
    super();
  }

  connectedCallback() {
    const authorId = this.getAttribute('author-id')
    const userId = this.getAttribute('user-id')
    const content = this.getAttribute('content')

    const messageElement = document.createElement('p')
    messageElement.classList.add('message', 'text-sm', 'text-white', 'text-start', 'p-2', 'm-2')
    messageElement.innerText = content
    messageElement.setAttribute('data-bs-toggle', 'tooltip')

    if (authorId !== userId) messageElement.classList.add('message-end')

    new Tooltip(messageElement);

    this.appendChild(messageElement);
    this.innerHtml = messageElement.outerHTML;
  }
}
