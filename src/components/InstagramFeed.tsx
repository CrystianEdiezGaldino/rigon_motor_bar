
import React from 'react';
import { Button } from "@/components/ui/button";
import { Instagram, ExternalLink } from 'lucide-react';
import { motion } from 'framer-motion';

const InstagramFeed: React.FC = () => {
  return (
    <section className="relative py-24 md:py-32 overflow-hidden" id="instagram">
      {/* Background Effects */}
      <div className="absolute inset-0 opacity-20">
        <div className="absolute top-1/4 left-1/4 w-[600px] h-[600px] rounded-full bg-[#F45F0A]/10 blur-[120px] animate-pulse"></div>
        <div className="absolute bottom-1/3 right-1/3 w-[700px] h-[700px] rounded-full bg-[#F45F0A]/5 blur-[150px] animate-pulse" style={{animationDelay: '1s'}}></div>
      </div>

      <div className="container mx-auto px-4 relative z-10">
        {/* Title Section */}
        <div className="flex flex-col items-center mb-16">
          <motion.span 
            initial={{ opacity: 0, y: -10 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            viewport={{ once: true }}
            className="text-[#F45F0A] font-semibold mb-3 tracking-wider uppercase text-sm"
          >
            Siga Nosso
          </motion.span>
          <motion.h2 
            initial={{ opacity: 0, y: -15 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            viewport={{ once: true }}
            className="text-4xl md:text-6xl font-bold text-white text-center mb-8 tracking-tight"
          >
            Instagram
          </motion.h2>
          
          <motion.div 
            initial={{ scaleX: 0 }}
            whileInView={{ scaleX: 1 }}
            transition={{ duration: 0.7, delay: 0.2 }}
            viewport={{ once: true }}
            className="h-[2px] w-32 bg-gradient-to-r from-transparent via-[#F45F0A] to-transparent mb-8"
          ></motion.div>
          
          <motion.p 
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.3 }}
            viewport={{ once: true }}
            className="text-gray-300 max-w-2xl text-center text-lg"
          >
            Acompanhe nosso perfil <span className="font-bold text-[#F45F0A]">@rigonmotorbar</span> e fique por dentro de todas as novidades, eventos e momentos especiais.
          </motion.p>
        </div>

        {/* Instagram Embed */}
        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          viewport={{ once: true }}
          className="w-full max-w-4xl mx-auto mb-16"
        >
          <div className="relative pb-[93%] h-0">
            <iframe
              src="https://www.instagram.com/rigonmotorbar/embed/?theme=dark"
              className="absolute top-0 left-0 w-full h-full rounded-xl border border-zinc-800/30"
              frameBorder="0"
              scrolling="no"
              allowTransparency={true}
            ></iframe>
          </div>
        </motion.div>

        {/* Follow Button */}
        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.5 }}
          viewport={{ once: true }}
          className="flex justify-center"
        >
          <a 
            href="https://www.instagram.com/rigonmotorbar/" 
            target="_blank" 
            rel="noopener noreferrer"
            className="group relative"
          >
            <div className="absolute -inset-2 bg-gradient-to-r from-[#F45F0A] to-[#d54d02] rounded-xl opacity-20 blur transition-all duration-500 group-hover:opacity-30"></div>
            <Button 
              variant="outline" 
              className="relative bg-gradient-to-br from-zinc-900 to-black border-zinc-800/50 hover:border-[#F45F0A]/50 px-8 py-6 text-lg rounded-xl transition-all duration-500 hover:shadow-[0_0_30px_rgba(244,95,10,0.2)] text-gray-300 hover:text-white"
            >
              <Instagram className="h-6 w-6 mr-3 text-[#F45F0A] group-hover:scale-110 transition-transform duration-300" />
              Seguir no Instagram
              <ExternalLink className="h-4 w-4 ml-2 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform duration-300" />
            </Button>
          </a>
        </motion.div>
      </div>
    </section>
  );
};

export default InstagramFeed;
