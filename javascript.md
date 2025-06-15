## _javascript.js_

O arquivo `_javascript.js_` contém três funções utilitárias para facilitar a manipulação do **DOM**.

### **Seleção por ID**

Retorna um elemento com o ID especificado:

```js
function byId(id) {
  return document.getElementById(id);
}
```

---

### **Manipulação de Estilos**

Retorna o objeto `style` do primeiro elemento que corresponde ao seletor:

```js
function style(selector) {
  return document.querySelector(selector).style;
}
```

---

### **Seleção de Múltiplos Elementos**

Retorna uma **NodeList** contendo todos os elementos que correspondem ao seletor:

```js
function by(selector) {
  return document.querySelectorAll(selector);
}
```

**Melhoria potencial:** Se precisar iterar sobre os elementos retornados por `by(selector)`, pode usar `Array.from()` para transformá-los em um array manipulável.

---

```js
function byId(id) {
  return document.getElementById(id);
}

function style(selector) {
  return document.querySelector(selector).style;
}

function by(selector) {
  return document.querySelectorAll(selector);
}
```
