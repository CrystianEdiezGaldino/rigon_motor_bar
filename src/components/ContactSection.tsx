
import React from 'react';
import { Phone, Mail, MapPin, Instagram, Facebook } from 'lucide-react';

const ContactSection: React.FC = () => {
  return (
    <section className="bg-black text-white py-16 md:py-24" id="contato">
      <div className="container mx-auto px-4">
        <div className="flex flex-col items-center mb-12">
          <span className="text-[#F45F0A] font-semibold mb-2">FALE CONOSCO</span>
          <h2 className="text-3xl md:text-4xl font-bold mb-6 text-center">
            Contato
          </h2>
          <div className="h-1 w-40 bg-[#F45F0A] mx-auto"></div>
        </div>
        
        <div className="max-w-4xl mx-auto">
          <div className="grid md:grid-cols-2 gap-12">
            <div>
              <h3 className="text-2xl font-semibold mb-6">Horário de Funcionamento</h3>
              <div className="space-y-3 mb-8">
                <p className="flex justify-between border-b border-zinc-800 pb-2">
                  <span className="font-medium">Terça a Sábado:</span>
                  <span className="text-gray-400">9h00 – 22h00</span>
                </p>
                <p className="flex justify-between border-b border-zinc-800 pb-2">
                  <span className="font-medium">Domingo:</span>
                  <span className="text-gray-400">9h00 – 22h00</span>
                </p>
                <p className="flex justify-between border-b border-zinc-800 pb-2">
                  <span className="font-medium">Segunda:</span>
                  <span className="text-gray-400">Fechado</span>
                </p>
              </div>
              
              <h3 className="text-2xl font-semibold mb-6">Informações de Contato</h3>
              <div className="space-y-4">
                <p className="flex items-center">
                  <Phone className="w-5 h-5 text-[#F45F0A] mr-3" />
                  <span>(41) 99999-9999</span>
                </p>
                <p className="flex items-center">
                  <Phone className="w-5 h-5 text-[#F45F0A] mr-3" />
                  <span>(41) 99999-9999 (WhatsApp)</span>
                </p>
                <p className="flex items-center">
                  <Mail className="w-5 h-5 text-[#F45F0A] mr-3" />
                  <span>contato@rigonbar.com.br</span>
                </p>
                <p className="flex items-center">
                  <MapPin className="w-5 h-5 text-[#F45F0A] mr-3" />
                  <span>Rua São Pedro, 159 - Jd Menino Deus, Quatro Barras - PR</span>
                </p>
              </div>
              
              <div className="mt-8 flex space-x-4">
                <a href="https://instagram.com/rigonmotorbar" target="_blank" rel="noopener noreferrer" className="bg-zinc-900 hover:bg-zinc-800 p-3 rounded-full transition-colors">
                  <Instagram className="w-5 h-5 text-[#F45F0A]" />
                </a>
                <a href="https://facebook.com/RigonMotorBar" target="_blank" rel="noopener noreferrer" className="bg-zinc-900 hover:bg-zinc-800 p-3 rounded-full transition-colors">
                  <Facebook className="w-5 h-5 text-[#F45F0A]" />
                </a>
              </div>
            </div>
            
            <div>
              <h3 className="text-2xl font-semibold mb-6">Localização</h3>
              <div className="w-full h-[300px] rounded-lg overflow-hidden">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3602.8647988544584!2d-49.07645772374619!3d-25.37145623679271!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dcefb6c1f2f9ed%3A0x6f6f6f6f6f6f6f6f!2sRua%20S%C3%A3o%20Pedro%2C%20159%20-%20Jardim%20Menino%20Deus%2C%20Quatro%20Barras%20-%20PR%2C%2083420-000!5e0!3m2!1sen!2sbr!4v1689524167185!5m2!1sen!2sbr"
                  width="100%"
                  height="100%"
                  style={{ border: 0 }}
                  allowFullScreen={false}
                  loading="lazy"
                  referrerPolicy="no-referrer-when-downgrade"
                  className="grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-300"
                ></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default ContactSection;
