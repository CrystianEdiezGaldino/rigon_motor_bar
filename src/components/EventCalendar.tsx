
import React, { useState } from "react";
import { Calendar } from "@/components/ui/calendar";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Calendar as CalendarIcon, ChevronRight } from "lucide-react";
import { motion } from "framer-motion";

type CalendarEvent = {
  date: Date;
  title: string;
  type: "music" | "motorcycle" | "special" | "promotion";
  description?: string;
};

const EventCalendar: React.FC = () => {
  const today = new Date();
  const [selectedDate, setSelectedDate] = useState<Date | undefined>(today);
  const [month, setMonth] = useState<Date>(today);

  // Example events
  const events: CalendarEvent[] = [
    {
      date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 2),
      title: "Noite do Rock",
      type: "music",
      description: "Bandas de rock ao vivo a partir das 21h"
    },
    {
      date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 3),
      title: "Encontro de Motociclistas",
      type: "motorcycle",
      description: "Encontro semanal dos amantes de motos"
    },
    {
      date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 5),
      title: "DJ Night",
      type: "music",
      description: "Os melhores DJs da cidade"
    },
    {
      date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 6),
      title: "Happy Hour",
      type: "promotion",
      description: "Drinks com 30% de desconto"
    },
    {
      date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 9),
      title: "Aniversário Rigon",
      type: "special",
      description: "Comemore conosco o aniversário do Rigon Motor Bar"
    }
  ];

  const eventsOnSelectedDate = selectedDate 
    ? events.filter(event => 
        event.date.getDate() === selectedDate.getDate() &&
        event.date.getMonth() === selectedDate.getMonth() &&
        event.date.getFullYear() === selectedDate.getFullYear()
      )
    : [];

  // Function to check if a date has an event
  const hasEvent = (date: Date) => {
    return events.some(event => 
      event.date.getDate() === date.getDate() &&
      event.date.getMonth() === date.getMonth() &&
      event.date.getFullYear() === date.getFullYear()
    );
  };

  const getEventTypeColor = (type: string) => {
    switch(type) {
      case 'music': return 'bg-amber-500';
      case 'motorcycle': return 'bg-blue-500';
      case 'special': return 'bg-purple-500';
      case 'promotion': return 'bg-green-500';
      default: return 'bg-gray-500';
    }
  };

  const getEventTypeName = (type: string) => {
    switch(type) {
      case 'music': return 'Música';
      case 'motorcycle': return 'Encontro';
      case 'special': return 'Especial';
      case 'promotion': return 'Promoção';
      default: return 'Evento';
    }
  };

  return (
    <motion.section 
      className="py-16 bg-black"
      initial={{ opacity: 0 }}
      whileInView={{ opacity: 1 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5 }}
    >
      <div className="container mx-auto px-4">
        <div className="flex flex-col lg:flex-row gap-8">
          <div className="lg:w-1/2">
            <motion.div
              initial={{ x: -20, opacity: 0 }}
              whileInView={{ x: 0, opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5 }}
            >
              <Calendar
                mode="single"
                selected={selectedDate}
                onSelect={setSelectedDate}
                onMonthChange={setMonth}
                month={month}
                className="border border-zinc-800 rounded-xl p-4 bg-zinc-900/50 backdrop-blur-lg"
                classNames={{
                  day_selected: "bg-[#F45F0A] text-white hover:bg-[#d54d02]",
                  day_today: "bg-zinc-800 text-white",
                  day: hasEvent(today) ? "relative" : ""
                }}
                modifiersStyles={{
                  selected: {
                    fontWeight: "bold"
                  }
                }}
                components={{
                  DayContent: ({ date }) => {
                    const currentDate = new Date(date);
                    const hasEventOnDay = hasEvent(currentDate);
                    const dayNumber = currentDate.getDate();
                    
                    return (
                      <div className="relative w-full h-full flex items-center justify-center">
                        {dayNumber}
                        {hasEventOnDay && (
                          <div className="absolute bottom-1 w-1 h-1 bg-[#F45F0A] rounded-full"></div>
                        )}
                      </div>
                    );
                  }
                }}
              />
            </motion.div>
          </div>
          
          <div className="lg:w-1/2">
            <motion.div
              initial={{ x: 20, opacity: 0 }}
              whileInView={{ x: 0, opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <Card className="bg-zinc-900/50 backdrop-blur-lg border-zinc-800 shadow-lg h-full">
                <CardHeader className="pb-3">
                  <CardTitle className="text-xl font-semibold flex items-center gap-2">
                    <CalendarIcon className="h-5 w-5 text-[#F45F0A]" />
                    {selectedDate ? (
                      <>
                        Eventos em {selectedDate.getDate()}/{selectedDate.getMonth() + 1}/{selectedDate.getFullYear()}
                      </>
                    ) : (
                      <>Selecione uma data</>
                    )}
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  {eventsOnSelectedDate.length > 0 ? (
                    <div className="space-y-4">
                      {eventsOnSelectedDate.map((event, index) => (
                        <motion.div
                          key={index}
                          initial={{ opacity: 0, y: 10 }}
                          animate={{ opacity: 1, y: 0 }}
                          transition={{ duration: 0.3, delay: index * 0.1 }}
                          className="p-4 border border-zinc-800/50 rounded-lg hover:border-[#F45F0A]/30 transition-colors"
                        >
                          <div className="flex justify-between items-start mb-2">
                            <h3 className="font-medium text-lg">{event.title}</h3>
                            <Badge className={`${getEventTypeColor(event.type)} text-white`}>
                              {getEventTypeName(event.type)}
                            </Badge>
                          </div>
                          <p className="text-gray-400 text-sm mb-3">{event.description}</p>
                          <Button 
                            size="sm" 
                            variant="ghost" 
                            className="text-[#F45F0A] hover:text-white hover:bg-[#F45F0A]/20 p-0 h-auto"
                          >
                            Saiba mais <ChevronRight className="h-4 w-4 ml-1" />
                          </Button>
                        </motion.div>
                      ))}
                    </div>
                  ) : (
                    <div className="py-8 text-center">
                      <p className="text-gray-400">
                        {selectedDate 
                          ? "Não há eventos agendados para esta data."
                          : "Selecione uma data para ver os eventos."
                        }
                      </p>
                    </div>
                  )}
                </CardContent>
              </Card>
            </motion.div>
          </div>
        </div>
      </div>
    </motion.section>
  );
};

export default EventCalendar;
