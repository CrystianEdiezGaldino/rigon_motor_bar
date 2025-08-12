import '@/styles/globals.css';
import type { AppProps } from 'next/app';
import { DefaultSeo } from 'next-seo';
import SEOConfig from '../../next-seo.config';

export default function App({ Component, pageProps }: AppProps) {
  return (
    <>
      <DefaultSeo {...SEOConfig} />
      <Component {...pageProps} />
    </>
  );
}