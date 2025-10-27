<template>
  <div class="space-y-4">
    <div v-for="(item, idx) in items" :key="idx" class="bg-white p-4 rounded shadow">
      <button @click="toggle(idx)" class="w-full text-left flex justify-between items-center">
        <span class="font-semibold">{{ item.question }}</span>
        <span class="text-xl transition-transform duration-200" :class="{'rotate-180': open === idx}">{{ open === idx ? '−' : '+' }}</span>
      </button>
      <transition name="accordion" appear>
        <div v-show="open === idx" class="mt-2 text-gray-700 overflow-hidden" v-html="item.answer"></div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FaqAccordion',
  data() {
    return {
      open: null,
      items: [
        {
          question: 'Proč studovat právě v Irsku?',
          answer: `Irsko nabízí kombinaci vysoké kvality vzdělávání, přátelského prostředí a výuky v angličtině. Náš tým žije v Irsku a zná systém "zevnitř", takže ti poskytneme specializované poradenství zaměřené právě na tuto destinaci.`,
        },
        {
          question: 'Musím umět anglicky na vysoké úrovni, abych mohl studovat v Irsku?',
          answer: `Základní předpoklad je středně pokročilá angličtina, protože většina programů probíhá v angličtině. Některé školy vyžadují jazykový test (např. IELTS, Duolingo). Pomůžeme ti odhadnout, zda už na jazyk stačíš, nebo zda je vhodné se připravit.`,
        },
        {
          question: 'Je výhodné studovat v Irsku oproti ČR nebo jiným zemím?',
          answer: `Ano. Irsko nabízí kvalitní univerzity, studium v angličtině a často nižší náklady než jiné anglicky mluvící destinace. Naše specializace na Irsko znamená, že ti poradíme konkrétně k místním školám, oborům a životním podmínkám.`,
        },
        {
          question: 'Jaký je přijímací proces na irské vysoké školy?',
          answer: `Přihlášky se podávají buď přes centrální systém (např. CAO) nebo přímo školám, v závislosti na programu. Důležité je dodržet termíny, správně vyplnit údaje a dodat všechny požadované dokumenty. My tě provedeme krok za krokem.`,
        },
        {
          question: 'Musí být moje české vysvědčení přeloženo a úředně ověřeno?',
          answer: `Ano — většina univerzit požaduje překlad dokumentů do angličtiny a jejich ověření. Poradíme ti, jaké formy ověření univerzity vyžadují a kde najít ověřeného překladatele či notáře.`,
        },
        {
          question: 'Jak dlouho trvá, než bude rozhodnuto o přijetí?',
          answer: `Termíny se liší podle školy a programu. Obvykle univerzita vydá rozhodnutí několik týdnů po uzávěrce přihlášek. My tě upozorníme na specifické termíny a pomůžeme sledovat stav přihlášky.`,
        },
        {
          question: 'Kolik stojí studium v Irsku a jsou dostupná stipendia?',
          answer: `Výše školného a životních nákladů závisí na škole, programu a městě. Stipendia jsou dostupná, ale často konkurenceschopná. Vysvětlíme ti orientační náklady a kde hledat stipendia.`,
        },
        {
          question: 'Mohu pracovat během studia v Irsku?',
          answer: `Ano — mezinárodní studenti obvykle mohou pracovat omezený počet hodin týdně (obvykle až 20 hodin během semestru a více během prázdnin). Pomůžeme ti, jak práci skloubit se studiem.`,
        },
        {
          question: 'Jak si spočítat životní náklady v Irsku?',
          answer: `Životní náklady závisí na městě, ubytování a životním stylu. V rámci našich balíčků obdržíš příklady nákladů (ubytování, doprava, jídlo), aby sis mohl udělat reálný rozpočet.`,
        },
        {
          question: 'Jak vypadá studentský život v Irsku?',
          answer: `Studentský život je bohatý: kampusy, studentské kluby, sportovní a společenské aktivity. Univerzity nabízejí podporu pro zahraniční studenty a my ti pomůžeme se začleněním a seznámením s místní komunitou.`,
        },
        {
          question: 'Pomůžete mi i po příjezdu do Irska?',
          answer: `Ano — naše služby zahrnují osobní setkání po příjezdu a praktickou podporu (PPS number, bankovní účet, SIM karta, orientace ve městě). Spolupráce nekončí odesláním přihlášky.`,
        },
        {
          question: 'Co když chci změnit obor nebo školu?',
          answer: `Změna je možná, ale může vyžadovat administrativní kroky. Pomůžeme ti vybrat nejlepší postup a minimalizovat dopady na tvůj plán studia.`,
        },
        {
          question: 'Jaké balíčky nabízíte a v čem se liší?',
          answer: `Nabízíme Balíček START (vedeš většinu procesu s naší podporou) a Balíček PREMIUM (kompletní servis na klíč). Oba obsahují osobní setkání po příjezdu; PREMIUM nabízí více individuálního vedení a kontroly dokumentů.`,
        },
        {
          question: 'Jak mohu začít spolupráci?',
          answer: `Stačí vyplnit kontaktní formulář nebo rezervovat konzultaci. Domluvíme úvodní schůzku, zjistíme tvé cíle a navrhneme další kroky.`,
        },
        {
          question: 'Co když mě nepřijmou na žádnou školu?',
          answer: `Nabízíme alternativní řešení: poradíme s jinými obory nebo školami, upravíme strategii a pomůžeme připravit lepší přihlášky pro další rok. Přijetí neurčují my, ale uděláme maximum, aby ses připravil co nejlépe.`,
        }
      ],
    };
  },
  methods: {
    toggle(i) {
      this.open = this.open === i ? null : i;
    },
  },
};
</script>

<style scoped>
summary { cursor: pointer; }

/* simple accordion height transition */
.accordion-enter-active, .accordion-leave-active {
  transition: max-height 300ms cubic-bezier(.4,0,.2,1), opacity 250ms ease;
}
.accordion-enter-from, .accordion-leave-to {
  max-height: 0;
  opacity: 0;
}
.accordion-enter-to, .accordion-leave-from {
  max-height: 400px; /* reasonable max for answer content */
  opacity: 1;
}

.text-xl.rotate-180 { transform: rotate(180deg); }
</style>
