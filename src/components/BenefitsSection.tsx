
import React from 'react';
import { Music, Coffee, Beer, Calendar, Users, Utensils } from 'lucide-react';

interface BenefitCardProps {
  icon: React.ReactNode;
  title: string;
  description: string;
}

const BenefitCard: React.FC<BenefitCardProps> = ({ icon, title, description }) => {
  return (
    <div className="group bg-gradient-to-b from-zinc-900 to-zinc-950 p-8 rounded-2xl border border-zinc-800/50 hover:border-[#F45F0A]/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(244,95,10,0.1)]">
      <div className="h-14 w-14 bg-gradient-to-br from-[#F45F0A]/30 to-[#F45F0A]/10 rounded-xl flex items-center justify-center mb-6 text-[#F45F0A] transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
        {icon}
      </div>
      <h3 className="text-2xl font-bold mb-4 text-white group-hover:text-[#F45F0A] transition-colors duration-300">{title}</h3>
      <p className="text-gray-400 leading-relaxed">{description}</p>
    </div>
  );
};

const BenefitsSection: React.FC = () => {
  const benefits = [
    {
      icon: <Utensils className="h-7 w-7" />,
      title: "Gastronomia Exclusiva",
      description: "Cardápio sofisticado com opções únicas, desde hambúrgueres gourmet até pratos elaborados com ingredientes premium."
    },
    {
      icon: <Beer className="h-7 w-7" />,
      title: "Bar Completo",
      description: "Drinks autorais, carta de vinhos selecionados e as melhores cervejas artesanais para todos os gostos."
    },
    {
      icon: <Music className="h-7 w-7" />,
      title: "Entretenimento",
      description: "Programação musical diversificada com shows ao vivo, DJs residentes e performances especiais."
    },
    {
      icon: <Calendar className="h-7 w-7" />,
      title: "Eventos Especiais",
      description: "Noites temáticas, festas exclusivas e eventos personalizados para momentos memoráveis."
    },
    {
      icon: <Coffee className="h-7 w-7" />,
      title: "Ambiente Sofisticado",
      description: "Espaço único com design contemporâneo, iluminação especial e áreas dedicadas para diferentes experiências."
    },
    {
      icon: <Users className="h-7 w-7" />,
      title: "Atendimento Premium",
      description: "Equipe altamente treinada para proporcionar uma experiência excepcional em todos os momentos."
    }
  ];

  return (
    <section className="relative py-24 md:py-32 bg-gradient-to-b from-black via-zinc-950 to-black overflow-hidden" id="diferenciais">
      {/* Background Effects */}
      <div className="absolute inset-0 opacity-30">
        <div className="absolute top-1/3 left-1/4 w-[500px] h-[500px] rounded-full bg-[#F45F0A]/10 blur-[100px] animate-pulse"></div>
        <div className="absolute bottom-1/3 right-1/4 w-[600px] h-[600px] rounded-full bg-[#F45F0A]/5 blur-[120px] animate-pulse" style={{animationDelay: '1s'}}></div>
      </div>

      <div className="container mx-auto px-4 relative z-10">
        <div className="flex flex-col items-center mb-20">
          <span className="text-[#F45F0A] font-semibold mb-3 tracking-wider uppercase text-sm">Nossos Diferenciais</span>
          <h2 className="text-4xl md:text-5xl font-bold text-white text-center mb-6 tracking-tight">
            Por que somos únicos
          </h2>
          <div className="h-[2px] w-32 bg-gradient-to-r from-transparent via-[#F45F0A] to-transparent mb-8"></div>
          <p className="text-gray-300 max-w-3xl text-center text-lg">
            Combinamos ambiente sofisticado, gastronomia de primeira e entretenimento de qualidade para criar momentos inesquecíveis em cada visita.
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-10">
          {benefits.map((benefit, index) => (
            <BenefitCard
              key={index}
              icon={benefit.icon}
              title={benefit.title}
              description={benefit.description}
            />
          ))}
        </div>
      </div>
    </section>
  );
};

export default BenefitsSection;
