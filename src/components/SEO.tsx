import React from 'react';
import { Helmet } from 'react-helmet-async';

interface SEOProps {
  title?: string;
  description?: string;
  canonical?: string;
  ogImage?: string;
  noindex?: boolean;
  keywords?: string;
  author?: string;
  type?: string;
  twitterHandle?: string;
  structuredData?: object;
}

const SEO: React.FC<SEOProps> = ({
  title = 'Rigon Motor Bar - Experiências Gastronômicas e Entretenimento',
  description = 'O Rigon Motor Bar é um espaço único em Curitiba com gastronomia premium, drinks exclusivos e entretenimento de qualidade. Conheça nosso ambiente sofisticado e programação diversificada.',
  canonical = 'https://rigonmotorbar.com.br',
  ogImage = '/assets/imagens/shared_rigon.jpg',
  noindex = false,
  keywords = 'bar temático, motor bar, carreta bar, curitiba, drinks, música ao vivo, gastronomia, motos, entretenimento, eventos, área vip, lounge',
  author = 'Rigon Motor Bar',
  type = 'website',
  twitterHandle = '@rigonmotorbar',
  structuredData,
}) => {
  const siteTitle = title.includes('Rigon Motor Bar') ? title : `${title} | Rigon Motor Bar`;
  const fullCanonical = canonical.startsWith('http') ? canonical : `https://rigonmotorbar.com.br${canonical}`;
  const fullOgImage = ogImage.startsWith('http') ? ogImage : `https://rigonmotorbar.com.br${ogImage}`;
  
  return (
    <Helmet>
      <title>{siteTitle}</title>
      <meta name="description" content={description} />
      <meta name="keywords" content={keywords} />
      <meta name="author" content={author} />
      <link rel="canonical" href={fullCanonical} />
      
      {/* Robots */}
      {noindex ? (
        <meta name="robots" content="noindex, nofollow" />
      ) : (
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
      )}
      
      {/* Open Graph / Facebook */}
      <meta property="og:type" content={type} />
      <meta property="og:url" content={fullCanonical} />
      <meta property="og:title" content={siteTitle} />
      <meta property="og:description" content={description} />
      <meta property="og:image" content={fullOgImage} />
      <meta property="og:image:width" content="1200" />
      <meta property="og:image:height" content="630" />
      <meta property="og:image:alt" content="Rigon Motor Bar - Bar temático em carreta" />
      <meta property="og:site_name" content="Rigon Motor Bar" />
      <meta property="og:locale" content="pt_BR" />
      
      {/* Twitter */}
      <meta property="twitter:card" content="summary_large_image" />
      <meta property="twitter:site" content={twitterHandle} />
      <meta property="twitter:creator" content={twitterHandle} />
      <meta property="twitter:url" content={fullCanonical} />
      <meta property="twitter:title" content={siteTitle} />
      <meta property="twitter:description" content={description} />
      <meta property="twitter:image" content={fullOgImage} />
      <meta property="twitter:image:alt" content="Rigon Motor Bar - Bar temático em carreta" />
    </Helmet>
  );
};

export default SEO;