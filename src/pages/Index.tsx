
import React from 'react';
import Navbar from '../components/Navbar';
import HeroSection from '../components/HeroSection';
import AboutSection from '../components/AboutSection';
import FeaturesSection from '../components/FeaturesSection';
import EventsSection from '../components/EventsSection';
import MenuHighlights from '../components/MenuHighlights';
import ContactSection from '../components/ContactSection';
import Footer from '../components/Footer';
import TestimonialsSection from '../components/TestimonialsSection';
import BenefitsSection from '../components/BenefitsSection';
import WhatsAppButton from '../components/WhatsAppButton';
import InstagramFeed from '../components/InstagramFeed';
import EventCalendar from '../components/EventCalendar';
import ImagesGallery from '../components/ImagesGallery';
import SEO from '../components/SEO';
import { getSEOConfig, getStructuredData } from '../lib/seo-config';
import { Toaster } from '@/components/ui/sonner';
import { motion, useScroll, useSpring } from 'framer-motion';

const Index = () => {
  const { scrollYProgress } = useScroll();
  const scaleX = useSpring(scrollYProgress, {
    stiffness: 100,
    damping: 30,
    restDelta: 0.001
  });

  const seoConfig = getSEOConfig('home');
  const structuredData = getStructuredData('restaurant');

  return (
    <div className="bg-black text-white min-h-screen flex flex-col items-center w-full relative">
      <SEO 
        title={seoConfig.title}
        description={seoConfig.description}
        canonical={seoConfig.canonical}
        keywords={seoConfig.keywords}
        ogImage={seoConfig.ogImage}
        structuredData={structuredData}
      />
      
      <motion.div 
        className="fixed top-0 left-0 right-0 h-1 bg-[#F45F0A] origin-left z-50"
        style={{ scaleX }}
      />
      
      <div className="w-full sticky top-0 z-40">
        <Navbar />
      </div>
      
      <div className="w-full">
        <HeroSection />
      </div>
      
      <div className="w-full">
        <AboutSection />
      </div>
      
      <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <BenefitsSection />
        <FeaturesSection />
      </div>
      
      <div className="w-full">
        <ImagesGallery />
      </div>
      
      <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <TestimonialsSection />
      </div>
      
      <div className="w-full">
        <EventsSection />
      </div>
      
      <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <InstagramFeed />
        <ContactSection />
      </div>
      
      <div className="w-full">
        <Footer />
      </div>
      
      <WhatsAppButton />
      <Toaster position="top-right" />
    </div>
  );
};

export default Index;
