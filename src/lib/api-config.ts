// Configuração centralizada da API
export const API_CONFIG = {
  // URL base da API - SEMPRE usar produção
  BASE_URL: 'https://rigonmotorbar.com.br/api',
  
  // Timeout das requisições
  TIMEOUT: parseInt(import.meta.env.VITE_API_TIMEOUT || '10000'),
  
  // Endpoints
  ENDPOINTS: {
    // Autenticação
    AUTH: '/endpoints/auth.php',
    
    // Produtos (requer autenticação)
    PRODUTOS: '/endpoints/produtos.php',
    
    // Cardápio público (não requer autenticação)
    CARDAPIO_PUBLICO: '/endpoints/cardapio-publico.php',
    
    // Testes
    TEST: '/test.php',
    TEST_DATABASE: '/test-database.php'
  },
  
  // URLs completas
  getUrl: (endpoint: string) => `${API_CONFIG.BASE_URL}${endpoint}`,
  
  // Headers padrão
  DEFAULT_HEADERS: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  
  // Informações do ambiente
  ENVIRONMENT: 'production',
  IS_DEV: false,
  IS_PROD: true
};

// Função para fazer requisições - SEMPRE usar API de produção
export const apiRequest = async (
  endpoint: string, 
  options: RequestInit = {}
) => {
  const url = API_CONFIG.getUrl(endpoint);
  
  try {
    console.log(`🔄 Conectando com API: ${url}`);
    
    const response = await fetch(url, {
      ...options,
      headers: {
        ...API_CONFIG.DEFAULT_HEADERS,
        ...options.headers,
      },
      signal: AbortSignal.timeout(API_CONFIG.TIMEOUT),
      mode: 'cors', // Forçar modo CORS
    });

    if (response.ok) {
      const data = await response.json();
      console.log(`✅ API funcionou: ${url}`, data);
      return { response, data, url };
    } else {
      console.error(`⚠️ API retornou erro ${response.status}: ${url}`, response);
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
  } catch (error) {
    console.error(`❌ Erro na API ${url}:`, error);
    
    // Log detalhado para debug
    if (error instanceof TypeError && error.message.includes('fetch')) {
      console.error('🔍 Possível problema de CORS ou conectividade');
      console.error('🔍 Verifique se a API está acessível em:', url);
      console.error('🔍 Teste diretamente no navegador:', url);
    }
    
    throw error;
  }
};
