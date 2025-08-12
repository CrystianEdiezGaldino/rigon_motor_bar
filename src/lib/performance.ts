// Configurações de performance e SEO
export const PERFORMANCE_CONFIG = {
  // Configurações de lazy loading
  lazyLoading: {
    images: {
      threshold: 0.1,
      rootMargin: '50px',
    },
    components: {
      threshold: 0.1,
      rootMargin: '100px',
    },
  },

  // Configurações de cache
  cache: {
    images: {
      maxAge: 31536000, // 1 ano
      staleWhileRevalidate: 86400, // 1 dia
    },
    static: {
      maxAge: 31536000, // 1 ano
      staleWhileRevalidate: 86400, // 1 dia
    },
  },

  // Configurações de compressão
  compression: {
    gzip: true,
    brotli: true,
    level: 6,
  },

  // Configurações de preload
  preload: {
    critical: [
      '/assets/imagens/logo.png',
      '/assets/imagens/shared_rigon.jpg',
    ],
    fonts: [
      'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
    ],
  },

  // Configurações de service worker
  serviceWorker: {
    enabled: true,
    scope: '/',
    cacheName: 'rigon-motor-bar-v1',
  },
};

// Função para lazy load de imagens
export const lazyLoadImage = (img: HTMLImageElement) => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target as HTMLImageElement;
        img.src = img.dataset.src || '';
        img.classList.remove('lazy');
        observer.unobserve(img);
      }
    });
  }, PERFORMANCE_CONFIG.lazyLoading.images);

  observer.observe(img);
};

// Função para preload de recursos críticos
export const preloadCriticalResources = () => {
  PERFORMANCE_CONFIG.preload.critical.forEach((resource) => {
    const link = document.createElement('link');
    link.rel = 'preload';
    link.as = 'image';
    link.href = resource;
    document.head.appendChild(link);
  });

  PERFORMANCE_CONFIG.preload.fonts.forEach((font) => {
    const link = document.createElement('link');
    link.rel = 'preload';
    link.as = 'style';
    link.href = font;
    document.head.appendChild(link);
  });
};

// Função para otimizar imagens
export const optimizeImage = (src: string, width?: number, height?: number) => {
  const url = new URL(src, window.location.origin);
  
  if (width) {
    url.searchParams.set('w', width.toString());
  }
  
  if (height) {
    url.searchParams.set('h', height.toString());
  }
  
  url.searchParams.set('q', '85');
  url.searchParams.set('fm', 'webp');
  
  return url.toString();
};

// Função para debounce
export const debounce = <T extends (...args: any[]) => any>(
  func: T,
  wait: number
): ((...args: Parameters<T>) => void) => {
  let timeout: NodeJS.Timeout;
  
  return (...args: Parameters<T>) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => func(...args), wait);
  };
};

// Função para throttle
export const throttle = <T extends (...args: any[]) => any>(
  func: T,
  limit: number
): ((...args: Parameters<T>) => void) => {
  let inThrottle: boolean;
  
  return (...args: Parameters<T>) => {
    if (!inThrottle) {
      func(...args);
      inThrottle = true;
      setTimeout(() => (inThrottle = false), limit);
    }
  };
};

// Função para medir performance
export const measurePerformance = (name: string, fn: () => void) => {
  const start = performance.now();
  fn();
  const end = performance.now();
  console.log(`${name} took ${end - start} milliseconds`);
};

// Função para registrar métricas de performance
export const reportPerformanceMetrics = () => {
  if ('performance' in window) {
    const navigation = performance.getEntriesByType('navigation')[0] as PerformanceNavigationTiming;
    
    const metrics = {
      dns: navigation.domainLookupEnd - navigation.domainLookupStart,
      tcp: navigation.connectEnd - navigation.connectStart,
      ttfb: navigation.responseStart - navigation.requestStart,
      domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
      load: navigation.loadEventEnd - navigation.loadEventStart,
    };
    
    console.log('Performance Metrics:', metrics);
    
    // Enviar métricas para analytics se necessário
    if (typeof window !== 'undefined' && (window as any).gtag) {
      (window as any).gtag('event', 'performance_metrics', metrics);
    }
  }
}; 