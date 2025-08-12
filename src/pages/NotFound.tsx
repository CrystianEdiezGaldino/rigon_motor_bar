import { useLocation } from "react-router-dom";
import { useEffect } from "react";
import SEO from "../components/SEO";
import { getSEOConfig } from "../lib/seo-config";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import { motion } from "framer-motion";

const NotFound = () => {
  const location = useLocation();

  useEffect(() => {
    console.error(
      "404 Error: User attempted to access non-existent route:",
      location.pathname
    );
  }, [location.pathname]);

  const seoConfig = getSEOConfig('notFound');

  return (
    <div className="min-h-screen bg-black text-white">
      <SEO 
        title={seoConfig.title}
        description={seoConfig.description}
        canonical={location.pathname}
        keywords={seoConfig.keywords}
        ogImage={seoConfig.ogImage}
        noindex={seoConfig.noindex}
      />
      
      <Navbar />
      
      <div className="flex items-center justify-center min-h-[80vh] px-4">
        <motion.div 
          className="text-center"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
        >
          <motion.h1 
            className="text-8xl font-bold mb-4 text-[#F45F0A]"
            initial={{ scale: 0.5 }}
            animate={{ scale: 1 }}
            transition={{ duration: 0.8, type: "spring" }}
          >
            404
          </motion.h1>
          
          <motion.h2 
            className="text-2xl font-semibold mb-4"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.3, duration: 0.6 }}
          >
            Página não encontrada
          </motion.h2>
          
          <motion.p 
            className="text-zinc-400 mb-8 max-w-md mx-auto"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.5, duration: 0.6 }}
          >
            A página que você está procurando não existe ou foi movida.
          </motion.p>
          
          <motion.a 
            href="/" 
            className="inline-block bg-[#F45F0A] text-white px-8 py-3 rounded-lg font-medium hover:bg-[#F45F0A]/90 transition-colors duration-300"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.7, duration: 0.6 }}
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
          >
            Voltar para o início
          </motion.a>
        </motion.div>
      </div>
      
      <Footer />
    </div>
  );
};

export default NotFound;
