const ELS = (sel, par) => (par || document).querySelectorAll(sel);
const EL = (sel, par) => (par || document).querySelector(sel);
const mod = (n, m) => (n % m + m) % m;

ELS(".slider-wrapper").forEach(EL_par => {
    const EL_slider = EL(".slider", EL_par);
    const ELS_items = ELS(".item", EL_par);
    const sub = +EL_par.dataset.items ?? 1;
    const tot = Math.ceil(ELS_items.length / sub);
    let c = 0;
    
    const anim = () => {
        for (let i = 0; i < EL_slider.classList.length; i++) { //rimuove tutte le classi che iniziano con 'slide-pos-'
            if (EL_slider.classList[i].startsWith('slide-pos-')) {
                EL_slider.classList.remove(EL_slider.classList[i]);
                i--;
            }
        }
        EL_slider.classList.add(`slide-pos-${c}`); //aggiunge la nuova classe per la posizione corrente
    };
    const prev = () => (c = mod(c-1, tot), anim());
    const next = () => (c = mod(c+1, tot), anim());
    
    EL(".prev", EL_par).addEventListener("click", prev);
    EL(".next", EL_par).addEventListener("click", next);
});

document.addEventListener('keydown', function(event) {
    if(event.key === "ArrowLeft")
      document.querySelector('.prev').click();
    else if(event.key === "ArrowRight")
        document.querySelector('.next').click();
});