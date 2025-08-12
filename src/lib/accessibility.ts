// Configurações de acessibilidade e SEO
export const ACCESSIBILITY_CONFIG = {
  // Configurações de navegação por teclado
  keyboardNavigation: {
    focusVisible: 'focus-visible:outline-2 focus-visible:outline-[#F45F0A] focus-visible:outline-offset-2',
    skipLink: {
      text: 'Pular para o conteúdo principal',
      className: 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-[#F45F0A] text-white px-4 py-2 rounded z-50',
    },
  },

  // Configurações de ARIA
  aria: {
    navigation: {
      role: 'navigation',
      'aria-label': 'Navegação principal',
    },
    main: {
      role: 'main',
      'aria-label': 'Conteúdo principal',
    },
    footer: {
      role: 'contentinfo',
      'aria-label': 'Rodapé',
    },
    button: {
      'aria-label': 'Botão',
    },
    link: {
      'aria-label': 'Link',
    },
  },

  // Configurações de contraste
  contrast: {
    primary: '#F45F0A',
    secondary: '#ffffff',
    background: '#000000',
    text: '#ffffff',
    muted: '#6b7280',
  },

  // Configurações de tamanho de fonte
  fontSize: {
    base: '16px',
    small: '14px',
    large: '18px',
    xlarge: '24px',
    xxlarge: '32px',
  },

  // Configurações de espaçamento
  spacing: {
    focus: '2px',
    outline: '2px',
    offset: '2px',
  },
};

// Função para aplicar configurações de acessibilidade
export const applyAccessibility = (element: HTMLElement, config: any) => {
  Object.entries(config).forEach(([key, value]) => {
    if (typeof value === 'string') {
      element.setAttribute(key, value);
    } else if (typeof value === 'object') {
      Object.entries(value).forEach(([subKey, subValue]) => {
        element.setAttribute(subKey, subValue as string);
      });
    }
  });
};

// Função para criar skip link
export const createSkipLink = () => {
  const skipLink = document.createElement('a');
  skipLink.href = '#main-content';
  skipLink.textContent = ACCESSIBILITY_CONFIG.keyboardNavigation.skipLink.text;
  skipLink.className = ACCESSIBILITY_CONFIG.keyboardNavigation.skipLink.className;
  return skipLink;
};

// Função para melhorar SEO de imagens
export const getImageSEO = (src: string, alt: string, width?: number, height?: number) => ({
  src,
  alt,
  width,
  height,
  loading: 'lazy' as const,
  decoding: 'async' as const,
});

// Função para melhorar SEO de links
export const getLinkSEO = (href: string, text: string, external = false) => ({
  href,
  text,
  target: external ? '_blank' : undefined,
  rel: external ? 'noopener noreferrer' : undefined,
  'aria-label': external ? `${text} (abre em nova aba)` : text,
}); 