
import React from 'react';
import { motion } from 'framer-motion';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { ArrowRight, Heart, Calendar } from 'lucide-react';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from "@/components/ui/carousel";

const ImagesGallery: React.FC = () => {
  const galleryImages = [
    {
      url: "/assets/imagens/unnamed7.jpg",
      date: "15/02/2024",
      likes: "324"
    },
    {
      url: "/assets/imagens/unnamed2.jpg",
      date: "14/02/2024",
      likes: "456"
    },
    {
      url: "/assets/imagens/unnamed1.jpg",
      date: "13/02/2024",
      likes: "289"
    },
    {
      url: "/assets/imagens/unnamed5.jpg",
      date: "12/02/2024",
      likes: "512"
    },
    {
      url: "/assets/imagens/unnamed4.jpg",
      date: "11/02/2024",
      likes: "378"
    },
    {
      url: "/assets/imagens/unnamed6.jpg",
      date: "10/02/2024",
      likes: "423"
    },
  ];

  return (
    <section className="relative bg-gradient-to-b from-black/95 to-black py-24" id="galeria">
      <div className="container mx-auto px-4 relative z-10">
        <div className="flex flex-col items-center mb-16">
          <motion.h2
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            transition={{ duration: 0.7 }}
            viewport={{ once: true }}
            className="text-4xl md:text-5xl font-bold mb-6 text-center"
          >
            Galeria de Imagens
          </motion.h2>
        </div>
        
        <div className="flex justify-center">
          <Carousel className="w-[280px]">
            <CarouselContent>
              {galleryImages.map((image, index) => (
                <CarouselItem key={index}>
                  <motion.div 
                    className="relative overflow-hidden rounded-lg"
                    whileHover={{ scale: 1.02 }}
                    transition={{ duration: 0.3 }}
                  >
                    <div className="w-[280px] h-[510px] relative">
                      <img 
                        src={image.url} 
                        alt={`Gallery image ${index + 1}`}
                        className="object-cover w-full h-full rounded-lg"
                      />
                      <div className="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                        <div className="flex justify-between items-center text-white">
                          <div className="flex items-center gap-2">
                            <Calendar className="h-4 w-4 text-[#F45F0A]" />
                            <span className="text-sm">{image.date}</span>
                          </div>
                          <div className="flex items-center gap-2">
                            <Heart className="h-4 w-4 text-[#F45F0A]" />
                            <span className="text-sm">{image.likes}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </motion.div>
                </CarouselItem>
              ))}
            </CarouselContent>
            <CarouselPrevious className="text-[#F45F0A] hover:text-white" />
            <CarouselNext className="text-[#F45F0A] hover:text-white" />
          </Carousel>
        </div>
        
        <motion.div 
          className="mt-10 text-center"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, delay: 0.3 }}
          viewport={{ once: true }}
        >
          <a 
            href="https://www.instagram.com/rigonmotorbar/"
            className="inline-flex items-center gap-2 text-[#F45F0A] font-medium hover:text-white transition-colors duration-300 text-lg"
          >
            Ver mais no Instagram
            <ArrowRight className="h-4 w-4" />
          </a>
        </motion.div>
      </div>
    </section>
  );
};

export default ImagesGallery;
