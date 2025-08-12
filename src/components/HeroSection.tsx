
import React, { useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import { ChevronDown } from 'lucide-react';
import { motion } from 'framer-motion';

const WhatsAppIcon = () => (
  <svg 
    viewBox="0 0 24 24" 
    width="24" 
    height="24" 
    fill="currentColor"
    className="text-xl"
  >
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
);

const HeroSection: React.FC = () => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [scrollY, setScrollY] = useState(0);
  
  useEffect(() => {
    setIsLoaded(true);
    
    const handleScroll = () => {
      setScrollY(window.scrollY);
    };
    
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const parallaxValue = Math.min(scrollY * 0.2, 100);
  
  return (
    <section 
      className="min-h-[100vh] bg-cover bg-center bg-no-repeat flex items-center justify-center relative bg-fixed overflow-hidden w-full" 
      id="home"
      style={{ 
        backgroundImage: "url('./assets/imagens/car6.jpg')",
        backgroundSize: `${160 + parallaxValue * 0.1}% auto`,
        backgroundPosition: `center ${4 + parallaxValue * 0.1}%`
      }}
    >
      {/* Party Light Rays Effect */}
      <div className="absolute top-0 left-0 w-full h-full z-[5] pointer-events-none overflow-hidden">
        {/* Center spotlight base */}
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[70vh] overflow-hidden">
          <motion.div 
            className="absolute top-0 w-full h-full bg-gradient-to-b from-white/15 via-white/5 to-transparent blur-[80px]"
            animate={{ opacity: [0.6, 0.8, 0.6] }}
            transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
          />
        </div>
        
        {/* Left light ray */}
        <motion.div 
          className="absolute top-0 left-[30%] h-[90vh] w-[80px] origin-top rotate-[-15deg] bg-gradient-to-b from-white/30 via-white/10 to-transparent blur-[15px]"
          animate={{ 
            opacity: [0.7, 0.2, 0.7],
            rotate: ["-15deg", "-18deg", "-15deg"]
          }}
          transition={{ 
            opacity: { duration: 3, repeat: Infinity, repeatType: "reverse" },
            rotate: { duration: 8, repeat: Infinity, repeatType: "reverse" }
          }}
        />
        
        {/* Right light ray */}
        <motion.div 
          className="absolute top-0 right-[30%] h-[80vh] w-[60px] origin-top rotate-[12deg] bg-gradient-to-b from-white/25 via-white/8 to-transparent blur-[12px]"
          animate={{ 
            opacity: [0.2, 0.6, 0.2],
            rotate: ["12deg", "15deg", "12deg"]
          }}
          transition={{ 
            opacity: { duration: 4, repeat: Infinity, repeatType: "reverse" },
            rotate: { duration: 7, repeat: Infinity, repeatType: "reverse" }
          }}
        />
        
        {/* Center light ray */}
        <motion.div 
          className="absolute top-0 left-1/2 -translate-x-1/2 h-[95vh] w-[100px] origin-top bg-gradient-to-b from-white/40 via-white/15 to-transparent blur-[20px]"
          animate={{ 
            opacity: [0.8, 0.3, 0.1],
            width: ["100px", "120px", "100px"]
          }}
          transition={{ 
            opacity: { duration: 5, repeat: Infinity, repeatType: "reverse" },
            width: { duration: 6, repeat: Infinity, repeatType: "reverse" }
          }}
        />
        
        {/* Additional angled rays */}
        <motion.div 
          className="absolute top-0 left-[15%] h-[70vh] w-[40px] origin-top rotate-[-25deg] bg-gradient-to-b from-white/20 via-white/5 to-transparent blur-[10px]"
          animate={{ opacity: [0.5, 0.1, 0.1] }}
          transition={{ duration: 3.5, repeat: Infinity, repeatType: "reverse" }}
        />
        
        <motion.div 
          className="absolute top-0 right-[15%] h-[75vh] w-[50px] origin-top rotate-[22deg] bg-gradient-to-b from-white/20 via-white/5 to-transparent blur-[10px]"
          animate={{ opacity: [0.5, 0.1, 0.1] }}
          transition={{ duration: 4.5, repeat: Infinity, repeatType: "reverse" }}
        />
      </div>

      {/* Enhanced overlay with multiple gradients */}
      <div className="absolute inset-0 bg-gradient-to-b from-black/90 via-black/50 to-black/90"></div>
      <div className="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-black/60"></div>
      
      {/* Content container */}
      <div className="container mx-auto px-4 text-center text-white relative z-10 flex flex-col justify-between h-full py-24">
        {/* Enhanced main content */}
        <motion.div 
          initial={{ opacity: 0, y: 50 }}
          animate={{ opacity: isLoaded ? 1 : 0, y: isLoaded ? 0 : 50 }}
          transition={{ duration: 0.8, delay: 0.5, ease: "easeOut" }}
          className="space-y-8"
        >
          <div className="flex justify-center">
            <img src="/assets/imagens/logo.png" alt="Logo" className="h-[300px] w-[300px]" />
          </div>
          
          {/* Enhanced animated divider */}
          <motion.div 
            className="relative h-1 mx-auto overflow-hidden max-w-md"
            initial={{ width: 0 }}
            animate={{ width: "12rem" }}
            transition={{ duration: 1.2, delay: 0.7 }}
          >
            <div className="absolute inset-0 bg-gradient-to-r from-transparent via-[#F45F0A] to-transparent"></div>
            <motion.div 
              className="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent"
              animate={{ x: ["-100%", "100%"] }}
              transition={{ duration: 2, repeat: Infinity, repeatDelay: 3 }}
            ></motion.div>
          </motion.div>
          
          <motion.p 
            className="text-2xl md:text-3xl text-gray-200 max-w-3xl mx-auto font-light"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.9 }}
          >
            O ponto de encontro perfeito para os amantes de motos e boa gastronomia
          </motion.p>
        </motion.div>
        
        {/* Enhanced buttons */}
        <motion.div 
          className="flex flex-col sm:flex-row items-center justify-center gap-8 pt-10"
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: isLoaded ? 1 : 0, y: isLoaded ? 0 : 30 }}
          transition={{ duration: 0.8, delay: 1.2 }}
        >
          <motion.div
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.98 }}
          >
            <Button 
              asChild
              className="bg-[#F45F0A] hover:bg-[#d54d02] text-white font-bold flex items-center gap-3 px-10 py-7 text-lg transition-all duration-300 shadow-[0_0_20px_rgba(244,95,10,0.3)] rounded-xl" 
              size="lg"
            >
              <a href="https://wa.me/your-number-here" className="flex items-center gap-3">
                <motion.div
                  animate={{ 
                    scale: [1, 1.1, 1],
                  }}
                  transition={{
                    duration: 1.5,
                    repeat: Infinity,
                    repeatType: "reverse"
                  }}
                >
                  <WhatsAppIcon />
                </motion.div>
                <span className="tracking-wide">Fale Conosco</span>
              </a>
            </Button>
          </motion.div>
          
          <motion.div
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.98 }}
          >
            <Button 
              asChild
              className="bg-white/5 hover:bg-[#F45F0A]/10 text-white border-2 border-[#F45F0A]/50 hover:border-[#F45F0A] hover:text-white px-10 py-7 text-lg transition-all duration-300 backdrop-blur-sm rounded-xl" 
              size="lg"
              variant="outline"
            >
              <a href="#eventos" className="tracking-wide">Próximos Eventos</a>
            </Button>
          </motion.div>
        </motion.div>
        
        {/* Enhanced scroll indicator */}
        <motion.div 
          className="flex flex-col items-center mt-auto"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 1, delay: 2 }}
        >
          <motion.a 
            href="#sobre" 
            className="text-white flex flex-col items-center group transition-all duration-300"
            animate={{ y: [0, 10, 0] }}
            transition={{ duration: 2, repeat: Infinity, ease: "easeInOut" }}
          >
            <span className="text-sm mb-2 opacity-80 group-hover:text-[#F45F0A] group-hover:opacity-100 tracking-wider">Rolar para baixo</span>
            <div className="relative p-2">
              <ChevronDown className="h-6 w-6 group-hover:text-[#F45F0A]" />
              <motion.div 
                animate={{ scale: [0, 1.5, 0] }}
                transition={{ duration: 2, repeat: Infinity }}
                className="absolute inset-0 bg-[#F45F0A]/20 rounded-full opacity-0 group-hover:opacity-100 blur-sm"
              ></motion.div>
            </div>
          </motion.a>
        </motion.div>
      </div>
    </section>
  );
};

export default HeroSection;
