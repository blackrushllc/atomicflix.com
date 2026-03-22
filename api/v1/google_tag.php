<?php
/**
 * AtomicFlix Google Tag Helper
 * Dynamically selects the Google Tag ID based on the current domain.
 */
$host = $_SERVER['HTTP_HOST'] ?? 'atomicflix.com';
$hostname = explode(':', $host)[0];
$GA_ID = ($hostname === 'dev.atomicflix.com') ? 'G-JEJ31MRE0B' : 'G-SY6N5FFBGG';
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $GA_ID; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $GA_ID; ?>');
</script>
