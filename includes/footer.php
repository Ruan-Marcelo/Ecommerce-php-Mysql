<?php
$base_path = $GLOBALS['lupiere_base_path'] ?? (basename(trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/')) === 'admin' ? '../' : '');
if (!empty($GLOBALS['lupiere_navbar_main_open'])):
?>
</main>
<?php endif; ?>

<!-- Footer -->
<footer
  class="bg-[#FAF9F4] w-full py-24 px-8 md:px-16 border-t border-[#1B3022]/10"
>
  <div
    class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12"
  >
    <div class="col-span-1 md:col-span-1">
      <div
        class="text-2xl font-headline-lg tracking-[0.3em] text-[#1B3022] uppercase mb-8"
      >
        LUPI&Egrave;RE
      </div>
      <p class="font-body-md text-[#1B3022]/60 max-w-xs">
        Elevando a alfaiataria cl&aacute;ssica para o homem contempor&acirc;neo. Rigor,
        tradi&ccedil;&atilde;o e personalidade.
      </p>
    </div>
    <div class="flex flex-col gap-4">
      <h4
        class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
      >
        Explorar
      </h4>
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>index.php"
        >In&iacute;cio</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>produtos.php"
        >Cole&ccedil;&otilde;es</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>acessorios.html"
        >Acess&oacute;rios</a
      >
    </div>
    <div class="flex flex-col gap-4">
      <h4
        class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
      >
        Atendimento ao Cliente
      </h4>
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>contato.php"
        >Envio &amp; Devolu&ccedil;&otilde;es</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>contato.php"
        >Pol&iacute;tica de Privacidade</a
      >
    </div>
    <div class="flex flex-col gap-4">
      <h4
        class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
      >
        Atelier
      </h4>
      <p class="font-body-md text-[#1B3022]/60">
        Avenida da Liberdade, 110<br />
        1250-146 Lisboa, Portugal
      </p>
      <div class="mt-4 flex gap-6">
        <a
          class="text-[#1B3022]/60 hover:text-[#1B3022]"
          href="https://www.instagram.com/uselupiere/"
          target="_blank"
          rel="noopener noreferrer"
        >
          <span class="material-symbols-outlined text-[20px]">
            photo_camera
          </span>
        </a>
        <a class="text-[#1B3022]/60 hover:text-[#1B3022]" href="mailto:info@lupiere.com" target="_blank" rel="noopener noreferrer">
          <span class="material-symbols-outlined text-[20px]">mail</span>
        </a>
      </div>
    </div>
  </div>
  <div
    class="max-w-[1440px] mx-auto mt-24 pt-12 border-t border-[#1B3022]/5 flex flex-col md:flex-row justify-between items-center gap-6"
  >
    <div
      class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase"
    >
      &copy; <?php echo date('Y'); ?> LUPI&Egrave;RE ALFAIATARIA. TODOS OS DIREITOS RESERVADOS.
    </div>
    <div class="flex gap-8">
      <a
        class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>contato.php"
        >Termos</a
      >
      <a
        class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>contato.php"
        >Cookies</a
      >
    </div>
  </div>
</footer>

<script src="<?php echo $base_path; ?>main.js"></script>
</body>
</html>
