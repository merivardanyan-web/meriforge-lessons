// Ծառայության էջ․ օրը կամ վարսավիրը փոխելիս ազատ ժամերը բերվում են /api/slots-ից՝ առանց վերբեռնման
// Առանց JS-ի նույն ֆորման աշխատում է GET-ով (ServiceController::show) — progressive enhancement

const filter = document.querySelector('[data-slot-filter]');
const list = document.querySelector('[data-slots]');
const status = document.querySelector('[data-slots-status]');

if (filter && list && status) {
    // JS-ը կա․ «Ցույց տալ ժամերը» կոճակն այլևս պետք չէ
    filter.querySelector('[data-no-js]')?.setAttribute('hidden', '');

    let controller = null;

    filter.addEventListener('change', async () => {
        const params = new URLSearchParams(new FormData(filter));
        if (!params.get('staff')) params.delete('staff');

        controller?.abort();                         // նախորդ հարցումը այլևս պետք չէ — JS Դաս 20
        controller = new AbortController();

        list.classList.add('is-loading');
        status.textContent = 'Բեռնվում է…';

        try {
            const response = await fetch(`${filter.dataset.api}?${params}`, {
                headers: { Accept: 'application/json' },
                signal: controller.signal,
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const { data, meta } = await response.json();
            render(data);
            status.textContent = data.length
                ? `${meta.label}․ ազատ է ${data.length} ժամ`
                : `${meta.label}․ ազատ ժամ չկա։ Ընտրիր այլ օր կամ վարսավիր։`;

            // Հասցեն համընկնում է ցուցադրվածի հետ․ վերբեռնելիս կամ կիսվելիս՝ նույն օրը
            history.replaceState(null, '', `${filter.action}?${params}`);
        } catch (error) {
            if (error.name === 'AbortError') return;
            status.textContent = 'Չհաջողվեց բեռնել ժամերը։ Սեղմիր «Ցույց տալ ժամերը»։';
            filter.querySelector('[data-no-js]')?.removeAttribute('hidden');
        } finally {
            list.classList.remove('is-loading');
        }
    });

    /** Ռադիո կոճակներ՝ նույն markup-ով, ինչ Blade-ում։ textContent՝ ոչ innerHTML (անուններն օգտագործողից են) */
    function render(slots) {
        const legend = list.querySelector('legend');
        list.replaceChildren(legend);

        for (const slot of slots) {
            const wrap = document.createElement('div');
            wrap.className = 'slot';

            const input = document.createElement('input');
            input.type = 'radio';
            input.name = 'slot_id';
            input.id = `slot-${slot.id}`;
            input.value = slot.id;

            const label = document.createElement('label');
            label.htmlFor = input.id;
            label.append(slot.time);
            const small = document.createElement('small');
            small.textContent = slot.staff.name;
            label.append(small);

            wrap.append(input, label);
            list.append(wrap);
        }
    }
}
