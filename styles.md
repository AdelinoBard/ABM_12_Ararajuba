## _styles.css_

A folha de estilo define a identidade visual do projeto, garantindo um design moderno, responsivo e agradável. Com uma paleta de cores bem estruturada e estilos que promovem **usabilidade**, cada componente recebe um toque refinado.

---

### **Paleta de Cores**

Utilizamos variáveis CSS para manter **consistência** e facilitar ajustes futuros:

```css
:root {
  --bluesky-light: #f0f2f5;
  --bluesky-dark: #1c2938;
  --bluesky-accent: #0079d3;
  --bluesky-border: #e5e7eb;
  --shadow-light: rgba(0, 0, 0, 0.1);
  --shadow-dark: rgba(0, 0, 0, 0.3);
}
```

---

### **Layout Global**

O **corpo da página** recebe uma fonte moderna e um esquema de cores harmonioso:

```css
body {
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  background-color: var(--bluesky-light);
  color: var(--bluesky-dark);
  line-height: 1.6;
}
```

---

### **Navbar Estilizada**

A barra de navegação possui **sombreamento elegante** para profundidade visual:

```css
.navbar {
  background-color: var(--bluesky-dark);
  box-shadow: 0px 2px 8px var(--shadow-dark);
}

.nav-link {
  font-weight: 600;
  transition: color 0.3s ease-in-out;
}

.nav-link:hover {
  color: var(--bluesky-accent);
}
```

---

### **Header Moderno**

O cabeçalho é **limpo e estruturado**, com sombras sutis para um efeito sofisticado:

```css
.header {
  background: var(--bluesky-light);
  border-bottom: 2px solid var(--bluesky-border);
  box-shadow: 0px 2px 5px var(--shadow-light);
  padding: 1rem 2rem;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
```

---

### **Botões com Design Intuitivo**

Os botões transmitem **ação**, com **efeitos de transição suaves** ao interagir:

```css
.button {
  background-color: var(--bluesky-accent);
  color: white;
  font-weight: 500;
  padding: 0.8rem 1.2rem;
  border-radius: 8px;
  transition: background 0.3s ease-in-out;
}

.button:hover {
  background-color: #0069c2;
}
```

---

### **Cards com Estilo Moderno**

Os **cards** ganham sombras leves e um efeito de _hover_ para destacar informações:

```css
.card {
  background: white;
  border-radius: 12px;
  border: 1px solid var(--bluesky-border);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0px 4px 10px var(--shadow-light);
  transition: transform 0.2s ease-in-out;
}

.card:hover {
  transform: translateY(-5px);
}
```

---

### **Responsividade Aprimorada**

O layout se **adapta elegantemente** para telas menores:

```css
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
  }

  .nav-buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
```

---

```css
/* Paleta de Cores */
:root {
  --bluesky-light: #f0f2f5;
  --bluesky-dark: #1c2938;
  --bluesky-accent: #0079d3;
  --bluesky-border: #e5e7eb;
  --shadow-light: rgba(0, 0, 0, 0.1);
  --shadow-dark: rgba(0, 0, 0, 0.3);
}

/* Body e Layout Global */
body {
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  background-color: var(--bluesky-light);
  color: var(--bluesky-dark);
  line-height: 1.6;
}

/* Navbar Estilizada */
.navbar {
  background-color: var(--bluesky-dark);
  box-shadow: 0px 2px 8px var(--shadow-dark);
}

.nav-link {
  font-weight: 600;
  transition: color 0.3s ease-in-out;
}

.nav-link:hover {
  color: var(--bluesky-accent);
}

/* Header Moderno */
.header {
  background: var(--bluesky-light);
  border-bottom: 2px solid var(--bluesky-border);
  box-shadow: 0px 2px 5px var(--shadow-light);
  padding: 1rem 2rem;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Estilos de Botões */
.button {
  background-color: var(--bluesky-accent);
  color: white;
  font-weight: 500;
  padding: 0.8rem 1.2rem;
  border-radius: 8px;
  transition: background 0.3s ease-in-out;
}

.button:hover {
  background-color: #0069c2;
}

/* Cards com Design Moderno */
.card {
  background: white;
  border-radius: 12px;
  border: 1px solid var(--bluesky-border);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0px 4px 10px var(--shadow-light);
  transition: transform 0.2s ease-in-out;
}

.card:hover {
  transform: translateY(-5px);
}

/* Responsividade */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
  }
  .nav-buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
```
