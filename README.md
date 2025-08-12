# 🍸 Motorbar Nightlife Web

**Desenvolvido por:** Crystian Ediez Galdino

Um projeto web moderno e responsivo para estabelecimentos noturnos, construído com as melhores tecnologias web atuais.

## 🚀 Tecnologias Utilizadas

- **Vite** - Build tool rápido e moderno
- **TypeScript** - Linguagem tipada para JavaScript
- **React** - Biblioteca para interfaces de usuário
- **shadcn/ui** - Componentes de UI modernos e acessíveis
- **Tailwind CSS** - Framework CSS utilitário
- **PostCSS** - Processador CSS avançado

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- **Node.js** (versão 16 ou superior)
- **npm** (gerenciador de pacotes do Node.js)
- **Git** (para controle de versão)

### Como instalar:

1. **Node.js**: Baixe em [nodejs.org](https://nodejs.org/)
2. **npm**: Vem junto com o Node.js
3. **Git**: Baixe em [git-scm.com](https://git-scm.com/)

## 🛠️ Instalação e Configuração

### 1. Clone o repositório
```bash
git clone https://github.com/CrystianEdiezGaldino/rigon_motor_bar.git
cd rigon_motor_bar
```

### 2. Instale as dependências
```bash
npm install
```

### 3. Inicie o servidor de desenvolvimento
```bash
npm run dev
```

O projeto estará disponível em `http://localhost:5173`

## 📁 Estrutura do Projeto

```
motorbar-nightlife-web/
├── public/                 # Arquivos estáticos
│   ├── assets/            # Imagens, vídeos e favicons
│   ├── robots.txt         # Configuração para SEO
│   └── sitemap.xml        # Mapa do site
├── src/                   # Código fonte
│   ├── components/        # Componentes React
│   │   ├── ui/           # Componentes de UI reutilizáveis
│   │   ├── HeroSection.tsx
│   │   ├── Navbar.tsx
│   │   ├── Footer.tsx
│   │   └── ...           # Outros componentes
│   ├── hooks/            # Hooks customizados
│   ├── lib/              # Utilitários e configurações
│   ├── pages/            # Páginas da aplicação
│   ├── App.tsx           # Componente principal
│   └── main.tsx          # Ponto de entrada
├── package.json           # Dependências e scripts
├── tailwind.config.ts     # Configuração do Tailwind CSS
├── vite.config.ts         # Configuração do Vite
└── tsconfig.json          # Configuração do TypeScript
```

## 🎯 Funcionalidades Principais

- **Design Responsivo** - Funciona perfeitamente em todos os dispositivos
- **Componentes Modulares** - Arquitetura limpa e reutilizável
- **SEO Otimizado** - Meta tags, sitemap e robots.txt configurados
- **Performance** - Carregamento rápido e otimizado
- **Acessibilidade** - Componentes seguindo padrões WCAG
- **UI Moderna** - Interface elegante com shadcn/ui

## 📜 Scripts Disponíveis

```bash
# Desenvolvimento
npm run dev          # Inicia servidor de desenvolvimento
npm run build        # Cria build de produção
npm run preview      # Visualiza build de produção
npm run lint         # Executa verificação de código

# Dependências
npm install          # Instala dependências
npm update           # Atualiza dependências
```

## 🚀 Deploy

### Build de Produção
```bash
npm run build
```

Os arquivos otimizados serão gerados na pasta `dist/`

### Deploy em Servidor Web
1. Execute `npm run build`
2. Faça upload da pasta `dist/` para seu servidor web
3. Configure o servidor para servir arquivos estáticos

## 🔧 Configurações

### Tailwind CSS
- Configurado em `tailwind.config.ts`
- Sistema de cores personalizado
- Componentes responsivos

### TypeScript
- Configuração estrita para melhor qualidade de código
- Tipos definidos para todos os componentes
- Interfaces bem estruturadas

### Vite
- Build rápido e otimizado
- Hot Module Replacement (HMR)
- Configuração para produção

## 📱 Responsividade

O projeto é totalmente responsivo e funciona em:
- 📱 Dispositivos móveis
- 💻 Tablets
- 🖥️ Desktops
- 📺 Telas grandes

## 🎨 Personalização

### Cores e Temas
- Edite `tailwind.config.ts` para personalizar cores
- Modifique variáveis CSS em `src/index.css`

### Componentes
- Todos os componentes estão em `src/components/`
- Fácil personalização e reutilização
- Sistema de props bem definido

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👨‍💻 Desenvolvedor

**Crystian Ediez Galdino**
- GitHub: [@CrystianEdiezGaldino](https://github.com/CrystianEdiezGaldino)
- Projeto: [Rigon Motor Bar](https://github.com/CrystianEdiezGaldino/rigon_motor_bar)

---

⭐ Se este projeto foi útil para você, considere dar uma estrela no repositório!
