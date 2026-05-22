<?php
$titulo_pagina = 'Contato';
require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>
<div class="flex-grow flex items-center justify-center py-section-gap px-gutter">
  <div class="max-w-container-max w-full">
    <!-- Hero Section -->
    <section
      class="max-w-container-max mx-auto px-margin-edge mb-section-gap"
    >
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-end">
        <div class="lg:col-span-7">
          <span
            class="font-label-caps text-label-caps text-secondary mb-4 block"
          >
            Personal Assistance
          </span>
          <h1 class="font-headline-display text-headline-display mb-8">
            The Concierge Service
          </h1>
          <p
            class="font-body-lg text-body-lg text-on-surface-variant max-w-xl italic"
          >
            Experience the pinnacle of bespoke tailoring with a dedicated
            stylist. From initial inquiry to final fitting, we offer an
            uncompromising level of service.
          </p>
        </div>
        <div class="lg:col-span-5 h-[500px] overflow-hidden">
          <img
            alt="Tailor at work"
            class="w-full h-full object-cover"
            data-alt="A close-up shot of a master tailor's hands meticulously pinning a charcoal grey wool suit jacket on a vintage wooden mannequin. The lighting is dramatic and moody, casting soft shadows across the rich fabric texture. The background is a blurred, high-end atelier with rolls of fine silk and linen in muted tones. The overall aesthetic is one of quiet, minimalist luxury and exceptional craft."
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDHW1fBIPaQ4hUVHe3Rf50DLEXvg2djVBhEVbM8zqBIpwRzUWKJIiJZL-XZAUTrHi8JCl_FAYqummkw4rbJl1hMraDU6ZY0pBU0fsUypuaFa7bSw11H1VTHYLwv6J9Glb1vwCQzGkqYZfleyjEELkxqURb0cASWFtXFd5Pu5u-dmL7mvidAEExMjk_85P83tUoiP1DnxPeE4XlrdikYSGskXUxKULyOXF8BUT-n-bZXZjzWHd7X7NlCJIYF2y7pj7IzWi0jL034Eo_"
          />
        </div>
      </div>
    </section>
    <!-- Contact & Atelier Section -->
    <section
      class="max-w-container-max mx-auto px-margin-edge mb-section-gap"
    >
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 lg:gap-32">
        <!-- Inquiry Form -->
        <div>
          <h2 class="font-headline-lg text-headline-lg mb-4">
            Private Consultation
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant mb-12">
            Submit your details below and a dedicated concierge will contact
            you within 24 hours.
          </p>
          <form action="processar_contato.php" method="POST" class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
              <div>
                <label
                  class="font-label-caps text-[10px] uppercase tracking-widest text-outline block mb-1"
                >
                  Full Name
                </label>
                <input
                  name="nome"
                  class="form-input-bespoke"
                  placeholder="ALEXANDER VANE"
                  type="text"
                  required
                />
              </div>
              <div>
                <label
                  class="font-label-caps text-[10px] uppercase tracking-widest text-outline block mb-1"
                >
                  Email Address
                </label>
                <input
                  name="email"
                  class="form-input-bespoke"
                  placeholder="CONTACT@LUPIERE.COM"
                  type="email"
                  required
                />
              </div>
            </div>
            <div>
              <label
                class="font-label-caps text-[10px] uppercase tracking-widest text-outline block mb-1"
              >
                Subject of Interest
              </label>
              <select
                name="assunto"
                class="form-input-bespoke"
              >
                <option value="">Selecione um assunto</option>
                <option value="BESPOKE TAILORING">BESPOKE TAILORING</option>
                <option value="MADE-TO-MEASURE">MADE-TO-MEASURE</option>
                <option value="CORPORATE GIFTING">CORPORATE GIFTING</option>
                <option value="WEDDING ATELIER">WEDDING ATELIER</option>
              </select>
            </div>
            <div>
              <label
                class="font-label-caps text-[10px] uppercase tracking-widest text-outline block mb-1"
              >
                Message
              </label>
              <textarea
                name="mensagem"
                class="form-input-bespoke resize-none"
                placeholder="YOUR REQUIREMENTS..."
                rows="4"
                required
              ></textarea>
            </div>
            <button
              type="submit"
              class="w-full py-5 bg-primary-container text-white font-label-caps tracking-[0.2em] uppercase hover:bg-primary transition-colors duration-300"
            >
              Request Appointment
            </button>
          </form>
        </div>
        <!-- Atelier Info -->
        <div
          class="bg-surface-container-low p-12 lg:p-20 flex flex-col justify-between"
        >
          <div>
            <h2 class="font-headline-md text-headline-md mb-8">
              Lisbon Atelier
            </h2>
            <div class="space-y-8">
              <div>
                <h4 class="font-label-caps text-secondary mb-2">Address</h4>
                <address
                  class="not-italic font-body-lg text-body-lg leading-relaxed"
                >
                  Avenida da Liberdade 192<br />
                  1250-147 Lisboa<br />
                  Portugal
                </address>
              </div>
              <div>
                <h4 class="font-label-caps text-secondary mb-2">Inquiries</h4>
                <p class="font-body-lg text-body-lg">concierge@lupiere.com</p>
                <p class="font-body-lg text-body-lg">+351 210 000 000</p>
              </div>
              <div>
                <h4 class="font-label-caps text-secondary mb-2">
                  Opening Hours
                </h4>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Monday — Friday: 10:00 - 19:00
                </p>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Saturday: By Appointment Only
                </p>
              </div>
            </div>
          </div>
          <div class="mt-16 pt-16 border-t border-outline-variant">
            <div
              class="h-64 w-full bg-surface-container-highest relative overflow-hidden"
            >
              <img
                alt="Lisbon Street"
                class="w-full h-full object-cover opacity-60 grayscale"
                data-alt="A wide-angle, high-contrast black and white architectural photograph of Lisbon's Avenida da Liberdade. The image features elegant historic buildings with ornate stone facades under a bright, high-key sky. The scene is clean and minimalist, reflecting a luxurious European atmosphere. The lighting is sharp, creating deep blacks and crisp whites that align with the brand's sophisticated and authoritative aesthetic."
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAsvM5ELmV3j4ccPDWK2ArXJv2OcL3N5sq87vtOjmgd8XcJ3Lv12Aqt0xF0980HNDKii_cn9JzlheX_xuhhLo8hBOmlbbSwansfx8z6eAFRpZIS6tF2tjfGhsDJs-NOSSqsLKg7di8KdpxMWtB7q0lLQwuEW1SVogICpEy618y3xajFnoQ6anJWyvoljuu0MiMqT2kGnSDvoWFCgTJZJka7WuOWD_ocHBAWb8iToNIEOpCWkA4dtMXOHX3haM35JpDKiiAkL5-1j6v_"
              />
              <div class="absolute inset-0 flex items-center justify-center">
                <a
                  class="px-6 py-3 bg-white/90 backdrop-blur-sm text-primary font-label-caps text-[10px] shadow-sm hover:bg-white transition-all"
                  href="https://www.google.com/maps?q=Avenida+da+Liberdade+192+Lisbon"
                  target="_blank"
                >
                  Open Map View
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Signature Quote -->
    <section class="py-section-gap bg-[#1B3022] text-stone-200">
      <div class="max-w-container-max mx-auto px-margin-edge text-center">
        <span class="font-label-caps text-secondary block mb-8"
          >João Pedro</span
        >
        <blockquote
          class="font-headline-display text-headline-lg max-w-4xl mx-auto italic leading-tight"
        >
          "O verdadeiro luxo não se encontra na ostentação do logotipo, mas na
          precisão discreta do ajuste e na intimidade do serviço."
        </blockquote>
      </div>
    </section>
  </div>
</div>
<?php
require_once __DIR__ . '/app/views/includes/footer.php';
?>