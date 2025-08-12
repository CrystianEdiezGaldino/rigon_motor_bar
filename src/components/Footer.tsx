
import React from 'react';
import { motion } from 'framer-motion';
import { Instagram, Facebook, MapPin, Mail, Phone } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';

const Footer: React.FC = () => {
  const currentYear = new Date().getFullYear();
  
  const socialLinks = [
    { icon: <Instagram size={20} />, url: "https://www.instagram.com/rigonmotorbar/" },
    { icon: <Facebook size={20} />, url: "#" },
  ];
  
  const footerLinks = [
    { name: 'Home', url: '#' },
    { name: 'Sobre', url: '#sobre' },
    { name: 'Cardápio', url: '#cardapio' },
    { name: 'Eventos', url: '#eventos' },
    { name: 'Contato', url: '#contato' },
  ];
  
  const contactInfo = [
    { icon: <MapPin size={16} />, text: "Rua São Pedro, 159 - Jd Menino Deus, Quatro Barras" },
    { icon: <Phone size={16} />, text: "(41) 99999-9999" },
    { icon: <Mail size={16} />, text: "contato@rigonmotorbar.com.br" },
  ];
  
  const container = {
    hidden: { opacity: 0 },
    show: {
      opacity: 1,
      transition: { staggerChildren: 0.05 }
    }
  };
  
  const item = {
    hidden: { y: 10, opacity: 0 },
    show: { y: 0, opacity: 1 }
  };
  
  return (
    <footer className="relative bg-black text-white pt-20 pb-8 overflow-hidden">
      {/* Background glow effect */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute top-1/4 left-1/4 w-[500px] h-[500px] rounded-full bg-[#F45F0A]/5 blur-[150px]"></div>
        <div className="absolute bottom-1/3 right-1/3 w-[600px] h-[600px] rounded-full bg-[#F45F0A]/3 blur-[170px]"></div>
      </div>
      
      <div className="container mx-auto px-4 relative z-10">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
          {/* Logo and about section */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            viewport={{ once: true }}
            className="space-y-4"
          >
            <h3 className="text-2xl font-bold tracking-tighter inline-flex flex-wrap items-center">
              <img src="/assets/imagens/logo2.webp" alt="Logo" className="h-16 w-16 mr-2" />
              RIGON <span className="text-[#F45F0A] relative inline-block mx-2">
                MOTOR
                <span className="absolute inset-0 bg-[#F45F0A]/20 blur-sm -z-10"></span>
              </span> BAR
            </h3>
            <p className="text-gray-400 leading-relaxed">
              O melhor ambiente para os apaixonados por motos em Quatro Barras. Gastronomia de qualidade, drinks especiais e eventos inesquecíveis.
            </p>
            
            <div className="flex space-x-3 pt-2">
              {socialLinks.map((link, i) => (
                <a
                  key={i}
                  href={link.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="bg-zinc-900 hover:bg-[#F45F0A]/90 text-white p-2.5 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-[0_0_15px_rgba(244,95,10,0.5)]"
                >
                  {link.icon}
                </a>
              ))}
            </div>
          </motion.div>
          
          {/* Quick Links */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.1 }}
            viewport={{ once: true }}
            className="space-y-4"
          >
            <h4 className="text-lg font-semibold mb-4 flex items-center">
              <span className="w-6 h-0.5 bg-[#F45F0A] mr-3"></span>
              Links Rápidos
            </h4>
            
            <motion.ul
              variants={container}
              initial="hidden"
              whileInView="show"
              viewport={{ once: true }}
              className="space-y-2"
            >
              {footerLinks.map((link, i) => (
                <motion.li key={i} variants={item}>
                  <a 
                    href={link.url}
                    className="text-gray-400 hover:text-[#F45F0A] transition-colors duration-300 inline-block py-1 relative group"
                  >
                    <span className="inline-block transform transition-transform duration-300 group-hover:translate-x-2">→</span>
                    <span className="ml-2">{link.name}</span>
                    <span className="absolute left-6 right-0 bottom-0 h-px bg-[#F45F0A]/50 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                  </a>
                </motion.li>
              ))}
            </motion.ul>
          </motion.div>
          
          {/* Contact Info */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            viewport={{ once: true }}
            className="space-y-4"
          >
            <h4 className="text-lg font-semibold mb-4 flex items-center">
              <span className="w-6 h-0.5 bg-[#F45F0A] mr-3"></span>
              Contato
            </h4>
            
            <motion.ul
              variants={container}
              initial="hidden"
              whileInView="show"
              viewport={{ once: true }}
              className="space-y-4"
            >
              {contactInfo.map((info, i) => (
                <motion.li
                  key={i}
                  variants={item}
                  className="flex items-start gap-3"
                >
                  <span className="bg-zinc-900 p-2 rounded-md text-[#F45F0A] mt-0.5">
                    {info.icon}
                  </span>
                  <span className="text-gray-400">{info.text}</span>
                </motion.li>
              ))}
            </motion.ul>
            
            <Button
              className="mt-4 bg-[#F45F0A] hover:bg-[#d54d02] text-white rounded-md transition-all duration-300 hover:shadow-[0_0_15px_rgba(244,95,10,0.4)]"
            >
              Faça sua reserva
            </Button>
          </motion.div>
        </div>
        
        <Separator className="bg-zinc-800 my-8" />
        
        <div className="text-center text-gray-500 text-sm">
          <p>&copy; {currentYear} Rigon Motor Bar. Todos os direitos reservados.</p>
          <p className="mt-2 text-xs">Desenvolvido por <a href="https://ediez-portfolio-showcase.lovable.app/" target="_blank" rel="noopener noreferrer" className="text-[#F45F0A] hover:text-[#d54d02] transition-colors duration-300">Crystian</a></p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
