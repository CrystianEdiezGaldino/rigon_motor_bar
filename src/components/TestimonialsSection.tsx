
import React from 'react';
import { Star } from 'lucide-react';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent } from '@/components/ui/card';

interface TestimonialProps {
  text: string;
  name: string;
  role: string;
  rating: number;
  imgSrc?: string;
}

const Testimonial: React.FC<TestimonialProps> = ({ text, name, role, rating, imgSrc }) => {
  return (
    <Card className="group bg-black border border-zinc-800/30 hover:border-[#F45F0A]/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(244,95,10,0.1)] h-full">
      <CardContent className="p-8 flex flex-col h-full">
        <div className="flex mb-6 gap-1">
          {[...Array(5)].map((_, i) => (
            <Star 
              key={i} 
              className={`h-5 w-5 transition-all duration-300 ${i < rating ? 'fill-[#F45F0A] text-[#F45F0A] group-hover:scale-110' : 'text-zinc-700'}`} 
            />
          ))}
        </div>
        <p className="text-gray-300 text-lg leading-relaxed mb-8 flex-grow">{text}</p>
        <div className="flex items-center gap-4">
          <Avatar className="h-12 w-12 ring-2 ring-[#F45F0A]/20 group-hover:ring-[#F45F0A]/40 transition-all duration-300">
            <AvatarImage src={imgSrc} alt={name} />
            <AvatarFallback className="bg-gradient-to-br from-[#F45F0A] to-[#d54d02] text-white font-medium text-lg">
              {name.split(' ').map(n => n[0]).join('')}
            </AvatarFallback>
          </Avatar>
          <div>
            <div className="font-semibold text-white text-lg group-hover:text-[#F45F0A] transition-colors duration-300">{name}</div>
            <div className="text-sm text-zinc-400">{role}</div>
          </div>
        </div>
      </CardContent>
    </Card>
  );
};

const TestimonialsSection: React.FC = () => {
  const testimonials = [
    {
      text: "Um ambiente excepcional com uma atmosfera única. A combinação perfeita de gastronomia, música e entretenimento. Cada visita é uma nova experiência!",
      name: "Ricardo Silva",
      role: "Cliente VIP",
      rating: 5
    },
    {
      text: "Os drinks são verdadeiras obras de arte e o atendimento é impecável. O lugar ideal para momentos especiais e encontros memoráveis.",
      name: "Camila Pereira",
      role: "Cliente Frequente",
      rating: 5
    },
    {
      text: "Uma descoberta incrível em São José dos Pinhais. A programação musical diversificada e o ambiente sofisticado fazem deste lugar um dos melhores da região!",
      name: "Marcelo Costa",
      role: "Cliente",
      rating: 4
    }
  ];

  return (
    <section className="relative py-24 md:py-32 bg-gradient-to-b from-black via-black to-black/95 overflow-hidden" id="depoimentos">
      {/* Background Effects */}
      <div className="absolute inset-0 opacity-20">
        <div className="absolute top-1/4 left-1/4 w-[600px] h-[600px] rounded-full bg-[#F45F0A]/10 blur-[120px] animate-pulse"></div>
        <div className="absolute bottom-1/3 right-1/3 w-[700px] h-[700px] rounded-full bg-[#F45F0A]/5 blur-[150px] animate-pulse" style={{animationDelay: '1s'}}></div>
      </div>

      <div className="container mx-auto px-4 relative z-10">
        <div className="flex flex-col items-center mb-20">
          <span className="text-[#F45F0A] font-semibold mb-3 tracking-wider uppercase text-sm">Depoimentos</span>
          <h2 className="text-4xl md:text-6xl font-bold text-white text-center mb-8 tracking-tight">
            O que dizem sobre nós
          </h2>
          <div className="h-[2px] w-32 bg-gradient-to-r from-transparent via-[#F45F0A] to-transparent mb-8"></div>
          <p className="text-gray-300 max-w-2xl text-center text-lg">
            Descubra por que nossos clientes consideram o Rigon Bar um lugar único e especial
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-10">
          {testimonials.map((testimonial, index) => (
            <Testimonial 
              key={index}
              text={testimonial.text}
              name={testimonial.name}
              role={testimonial.role}
              rating={testimonial.rating}
            />
          ))}
        </div>
      </div>
    </section>
  );
};

export default TestimonialsSection;
