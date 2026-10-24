// «Չեղարկել» առանց վերբեռնման․ fetch PATCH, CSRF token header-ով — PHP Դաս 17
// Առանց JS-ի ֆորման ուղարկվում է սովորական ձևով և սերվերը վերադարձնում է էջ

const table = document.querySelector('[data-bookings]');
const toast = document.querySelector('[data-toast]');
const token = document.querySelector('meta[name="csrf-token"]')?.content;

function notify(text) {
    toast.textContent = text;
    toast.classList.add('is-visible');
    clearTimeout(notify.timer);
    notify.timer = setTimeout(() => toast.classList.remove('is-visible'), 3500);
}

// Դելեգացիա․ մեկ լսիչ ամբողջ աղյուսակի համար — JS Դաս 12
table?.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-cancel]');
    if (!form) return;

    event.preventDefault();
    if (!confirm('Չեղարկե՞լ ամրագրումը։')) return;

    const button = form.querySelector('button');
    button.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'PATCH',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': token },
        });

        if (response.status === 419) {
            location.reload();                       // token-ը հնացել է․ նոր էջ՝ նոր token
            return;
        }

        const body = await response.json();

        if (!response.ok) {
            notify(body.message ?? 'Չհաջողվեց չեղարկել։');
            button.disabled = false;
            return;
        }

        const row = form.closest('tr');
        const badge = row.querySelector('[data-status]');
        badge.textContent = body.label;
        badge.className = `status status--${body.status}`;
        row.classList.add('row-muted');
        row.querySelectorAll('form').forEach((f) => f.remove());
        notify(body.message);
    } catch {
        notify('Կապը խզվեց։ Փորձիր կրկին։');
        button.disabled = false;
    }
});
