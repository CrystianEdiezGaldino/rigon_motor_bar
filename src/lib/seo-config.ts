export const SEO_CONFIG = {
  site: {
    name: 'Rigon Motor Bar',
    url: 'https://rigonmotorbar.com.br',
    description: 'O Rigon Motor Bar é uma mega carreta transformada em um bar temático espetacular para amantes de motos, boa música e ótima gastronomia.',
    keywords: 'bar temático, motor bar, carreta bar, curitiba, drinks, música ao vivo, gastronomia, motos, entretenimento, eventos, área vip, lounge',
    author: 'Rigon Motor Bar',
    locale: 'pt_BR',
    twitterHandle: '@rigonmotorbar',
  },
  
  pages: {
    home: {
      title: 'Rigon Motor Bar | Experiência Única sobre Rodas em Curitiba',
      description: 'O Rigon Motor Bar é uma mega carreta transformada em um bar temático espetacular para amantes de motos, boa música e ótima gastronomia. Localizado em Curitiba, oferecemos drinks exclusivos, música ao vivo e ambiente único.',
      keywords: 'bar temático, motor bar, carreta bar, curitiba, drinks, música ao vivo, gastronomia, motos, entretenimento, eventos, área vip, lounge',
      canonical: '/',
      ogImage: '/assets/imagens/shared_rigon.jpg',
      noindex: false,
    },
    
    menu: {
      title: 'Cardápio | Rigon Motor Bar',
      description: 'Conheça nosso cardápio exclusivo com hambúrgueres artesanais, drinks especiais, porções e cervejas artesanais. Sabores únicos em um ambiente temático de motos em Curitiba.',
      keywords: 'cardápio, hambúrgueres, drinks, cervejas, porções, gastronomia, curitiba, bar temático',
      canonical: '/cardapio',
      ogImage: '/assets/imagens/shared_rigon.jpg',
      noindex: false,
    },
    
    events: {
      title: 'Eventos | Rigon Motor Bar',
      description: 'Confira nossa programação de eventos, shows ao vivo e festas temáticas no Rigon Motor Bar. Entretenimento de qualidade em Curitiba.',
      keywords: 'eventos, shows, música ao vivo, festas, entretenimento, curitiba, bar temático',
      canonical: '/eventos',
      ogImage: '/assets/imagens/shared_rigon.jpg',
      noindex: false,
    },
    
    contact: {
      title: 'Contato | Rigon Motor Bar',
      description: 'Entre em contato com o Rigon Motor Bar. Reservas, informações e localização em Curitiba. Atendimento personalizado para seu evento.',
      keywords: 'contato, reservas, localização, curitiba, bar temático, eventos',
      canonical: '/contato',
      ogImage: '/assets/imagens/shared_rigon.jpg',
      noindex: false,
    },
    
    about: {
      title: 'Sobre | Rigon Motor Bar',
      description: 'Conheça a história do Rigon Motor Bar, uma carreta transformada em bar temático único em Curitiba. Experiência gastronômica e de entretenimento diferenciada.',
      keywords: 'sobre, história, carreta bar, bar temático, curitiba, gastronomia',
      canonical: '/sobre',
      ogImage: '/assets/imagens/shared_rigon.jpg',
      noindex: false,
    },
    
    notFound: {
      title: 'Página não encontrada | Rigon Motor Bar',
      description: 'A página que você está procurando não foi encontrada. Volte para a página inicial do Rigon Motor Bar.',
      keywords: '404, página não encontrada, erro',
      canonical: '/404',
      ogImage: '/assets/imagens/shared_rigon.jpg',
      noindex: true,
    },
  },
  
  structuredData: {
    restaurant: {
      "@context": "https://schema.org",
      "@type": "Restaurant",
      "name": "Rigon Motor Bar",
      "description": "Bar temático em carreta para amantes de motos, boa música e ótima gastronomia",
      "url": "https://rigonmotorbar.com.br",
      "logo": "https://rigonmotorbar.com.br/assets/imagens/logo.png",
      "image": "https://rigonmotorbar.com.br/assets/imagens/shared_rigon.jpg",
      "telephone": "+55-41-99999-9999",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Curitiba",
        "addressRegion": "PR",
        "addressCountry": "BR"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-25.4284",
        "longitude": "-49.2733"
      },
      "openingHours": "Th-Su 19:00-02:00",
      "priceRange": "$$",
      "servesCuisine": ["Brasileira", "Internacional"],
      "hasMenu": "https://rigonmotorbar.com.br/cardapio",
      "sameAs": [
        "https://www.instagram.com/rigonmotorbar",
        "https://www.facebook.com/rigonmotorbar"
      ]
    },
    
    organization: {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Rigon Motor Bar",
      "url": "https://rigonmotorbar.com.br",
      "logo": "https://rigonmotorbar.com.br/assets/imagens/logo.png",
      "sameAs": [
        "https://www.instagram.com/rigonmotorbar",
        "https://www.facebook.com/rigonmotorbar"
      ]
    },
    
    menu: {
      "@context": "https://schema.org",
      "@type": "Menu",
      "name": "Cardápio Rigon Motor Bar",
      "description": "Cardápio completo com hambúrgueres artesanais, drinks especiais e cervejas artesanais",
      "url": "https://rigonmotorbar.com.br/cardapio",
      "hasMenuSection": [
        {
          "@type": "MenuSection",
          "name": "Hambúrgueres",
          "hasMenuItem": [
            {
              "@type": "MenuItem",
              "name": "Road Burger",
              "description": "Hambúrguer artesanal, queijo cheddar, bacon crocante, cebola caramelizada e molho especial da casa",
              "offers": {
                "@type": "Offer",
                "price": "39.90",
                "priceCurrency": "BRL"
              }
            }
          ]
        }
      ]
    }
  }
};

export const getSEOConfig = (page: keyof typeof SEO_CONFIG.pages) => {
  return SEO_CONFIG.pages[page];
};

export const getStructuredData = (type: keyof typeof SEO_CONFIG.structuredData) => {
  return SEO_CONFIG.structuredData[type];
}; 