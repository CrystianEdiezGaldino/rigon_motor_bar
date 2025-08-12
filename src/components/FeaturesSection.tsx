
import React from 'react';
import { Music, Beer, Utensils, User, Calendar } from 'lucide-react';

type FeatureProps = {
  title: string;
  description: string;
  icon: React.ReactNode;
};

const Feature: React.FC<FeatureProps> = ({ icon, title, description }) => {
  return (
    <div className="bg-gradient-to-br from-zinc-900/40 to-black/40 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/30 hover:border-[#F45F0A]/50 transition-all duration-500 hover:-translate-y-2 group hover:shadow-[0_20px_40px_-15px_rgba(244,95,10,0.1)]">
      <div className="h-16 w-16 bg-gradient-to-br from-[#F45F0A]/20 to-[#F45F0A]/5 rounded-xl flex items-center justify-center mb-6 text-[#F45F0A] group-hover:bg-[#F45F0A]/30 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6">
        {icon}
      </div>
      <h3 className="text-2xl font-bold mb-4 text-white group-hover:text-[#F45F0A] transition-colors duration-300">{title}</h3>
      <p className="text-gray-400 leading-relaxed">{description}</p>
    </div>
  );
};

const FeaturesSection: React.FC = () => {
  const features = [
    {
      icon: <Beer className="w-8 h-8" />,
      title: "Bar Premium",
      description: "Drinks exclusivos, carta de vinhos selecionados e as melhores cervejas artesanais para uma experiência única."
    },
    {
      icon: <Utensils className="w-8 h-8" />,
      title: "Alta Gastronomia",
      description: "Cardápio elaborado com ingredientes selecionados, unindo sabor e apresentação em cada prato."
    },
    {
      icon: <Music className="w-8 h-8" />,
      title: "Entretenimento",
      description: "Programação musical diversificada com shows ao vivo, DJs e performances especiais todas as semanas."
    },
    {
      icon: <User className="w-8 h-8" />,
      title: "Ambiente Único",
      description: "Espaço sofisticado com diferentes ambientes, perfeito para todas as ocasiões e públicos."
    },
    {
      icon: <Calendar className="w-8 h-8" />,
      title: "Eventos Exclusivos",
      description: "Noites temáticas, festas especiais e eventos personalizados para momentos memoráveis."
    },
  ];

  return (
    <section className="relative py-24 md:py-32 bg-gradient-to-b from-black via-black to-black/95 overflow-hidden" id="diferenciais">
      {/* Background Effects */}
      <div className="absolute inset-0 opacity-20">
        <div className="absolute top-1/4 left-1/4 w-[600px] h-[600px] rounded-full bg-[#F45F0A]/10 blur-[120px] animate-pulse"></div>
        <div className="absolute bottom-1/3 right-1/3 w-[700px] h-[700px] rounded-full bg-[#F45F0A]/5 blur-[150px] animate-pulse" style={{animationDelay: '1s'}}></div>
      </div>

      <div className="container mx-auto px-4 relative z-10">
        <div className="flex flex-col items-center mb-20">
          <span className="text-[#F45F0A] font-semibold mb-3 tracking-wider uppercase text-sm">Experiência Única</span>
          <h2 className="text-4xl md:text-6xl font-bold text-white text-center mb-8 tracking-tight">
            Por que nos escolher
          </h2>
          <div className="h-[2px] w-32 bg-gradient-to-r from-transparent via-[#F45F0A] to-transparent mb-8"></div>
          <p className="text-gray-300 max-w-3xl text-center text-lg">
            Um espaço onde cada detalhe foi pensado para proporcionar momentos únicos e memoráveis em um ambiente sofisticado e acolhedor.
          </p>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-10">
          {features.map((feature, index) => (
            <Feature 
              key={index} 
              icon={feature.icon} 
              title={feature.title} 
              description={feature.description}
            />
          ))}
        </div>
      </div>
    </section>
  );
};

export default FeaturesSection;
