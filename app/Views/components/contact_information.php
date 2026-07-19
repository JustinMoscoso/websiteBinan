<?php
$phoneNumber = trim((string) ($phoneNumber ?? ''));
$landline = trim((string) ($landline ?? ''));
$emailAddress = trim((string) ($emailAddress ?? ''));
$officeAddress = trim((string) ($officeAddress ?? ''));

$makeTelHref = static function (string $number): string {
    $cleanNumber = preg_replace('/[^0-9+]/', '', $number) ?? '';
    return preg_replace('/(?!^)\+/', '', $cleanNumber) ?? $cleanNumber;
};

$contacts = array_values(array_filter([
    [
        'label' => 'Phone Number',
        'value' => $phoneNumber,
        'icon' => 'bi-telephone',
        'href' => $phoneNumber !== '' ? 'tel:' . $makeTelHref($phoneNumber) : '',
    ],
    [
        'label' => 'Landline',
        'value' => $landline,
        'icon' => 'bi-house-door',
        'href' => $landline !== '' ? 'tel:' . $makeTelHref($landline) : '',
    ],
    [
        'label' => 'Email Address',
        'value' => $emailAddress,
        'icon' => 'bi-envelope',
        'href' => $emailAddress !== '' ? 'mailto:' . $emailAddress : '',
    ],
    [
        'label' => 'Office Address',
        'value' => $officeAddress,
        'icon' => 'bi-geo-alt',
        'href' => '',
    ],
], static fn (array $contact): bool => $contact['value'] !== ''));
?>

<style>
  .contact-info-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    height: 100%;
    padding: 1.15rem;
    background: #fff;
    border: 1px solid #dbe8dd;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(27, 94, 32, 0.06);
    text-align: left;
  }

  .contact-info-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    color: #1b5e20;
    background: #e8f5e9;
    border-radius: 50%;
    font-size: 1.15rem;
  }

  .contact-info-label {
    display: block;
    margin-bottom: 0.2rem;
    color: #244a2b;
    font-size: 0.875rem;
    font-weight: 600;
  }

  .contact-info-value,
  .contact-info-value:visited {
    display: inline-flex;
    align-items: center;
    min-height: 44px;
    color: #52645a;
    line-height: 1.5;
    overflow-wrap: anywhere;
    text-decoration: none;
  }

  a.contact-info-value:hover,
  a.contact-info-value:focus-visible {
    color: #1b5e20;
    text-decoration: underline;
  }
</style>

<?php if ($contacts !== []): ?>
  <div class="row g-3 contact-info-grid">
    <?php foreach ($contacts as $contact): ?>
      <div class="col-md-6">
        <div class="contact-info-card">
          <span class="contact-info-icon" aria-hidden="true">
            <i class="bi <?= esc($contact['icon']) ?>"></i>
          </span>
          <div class="flex-grow-1">
            <span class="contact-info-label"><?= esc($contact['label']) ?></span>
            <?php if ($contact['href'] !== ''): ?>
              <a class="contact-info-value" href="<?= esc($contact['href']) ?>"><?= esc($contact['value']) ?></a>
            <?php else: ?>
              <span class="contact-info-value"><?= esc($contact['value']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="service-card text-center text-muted">No contact information is available.</div>
<?php endif; ?>
