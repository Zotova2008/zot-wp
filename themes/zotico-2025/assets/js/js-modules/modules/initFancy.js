/**
 * @var Fancybox
 */

export const initFancy = function () {
  Fancybox.bind('[data-fancybox]', {
    dragToClose: false,
  });

  // const fancyLink = document.querySelectorAll('[data-fancybox]');
  //
  // if (fancyLink.length > 0) {
  //   fancyLink.forEach((item) => {
  //     item.addEventListener('click', (i) => {
  //       const fancyContainer = document.querySelector('.fancybox__container');
  //       if (fancyContainer) {
  //         Fancybox.close();
  //       }
  //     });
  //   });
  // }

  function fancyEvent(target) {
    console.log('Fancybox event', target);
    // target: CSS-селектор строкой или сам HTMLElement
    if (!target) return;

    // Закрыть текущий инстанс (если есть), чтобы избежать наложений
    const inst = Fancybox.getInstance();
    if (inst) inst.close();

    const idModal = target.getAttribute('id');

    Fancybox.show(
      [
        {
          src: idModal,      // можно передать селектор или элемент
          type: "inline",
        },
      ],
      {
        dragToClose: false,
      }
    );
  }

// CF7: успешно отправлено — показать блок успеха
  document.addEventListener(
    "wpcf7mailsent",
    () => {
      const openSuccess = document.querySelector("#modal-success");
      if (openSuccess) {
        fancyEvent(openSuccess);
      }
    },
    false
  );

  // CF7: ошибка отправки — показать блок ошибки
  document.addEventListener(
    "wpcf7mailfailed",
    () => {
      const openError = document.querySelector("#modal-error");
      if (openError) {
        fancyEvent(openError);
      }
    },
    false
  );

  /* global Fancybox */

  // function ensureId(el) {
  //   if (!(el instanceof HTMLElement)) return null;
  //   if (el.id && typeof el.id === "string") return el.id;
  //   // присвоим временный id, если его нет
  //   const uid = "fbx-" + Math.random().toString(36).slice(2, 8);
  //   el.id = uid;
  //   return uid;
  // }
  //
  // /**
  //  * Открывает inline-модалку, принимая:
  //  *  - CSS-селектор строкой (#id или [data-...])
  //  *  - HTMLElement
  //  *  - NodeList / jQuery-подобный — возьмёт первый элемент
  //  */
  // export function fancyShow(target, opts = {}) {
  //   if (typeof Fancybox === "undefined") return;
  //
  //   let el = null;
  //
  //   if (typeof target === "string") {
  //     // если пришло что-то вроде [data-message-success] — найдём элемент
  //     el = target.startsWith("#") ? document.getElementById(target.slice(1)) : document.querySelector(target);
  //   } else if (target instanceof HTMLElement) {
  //     el = target;
  //   } else if (target && typeof target.length === "number") {
  //     // NodeList / jQuery-подобный
  //     el = target[0] instanceof HTMLElement ? target[0] : null;
  //   }
  //
  //   if (!el || !document.contains(el)) {
  //     console.warn("[Fancybox] HTML Element Not Found:", target, el);
  //     return;
  //   }
  //
  //   const id = ensureId(el);
  //   if (!id) return;
  //
  //   // закрыть прежний инстанс, если открыт
  //   const inst = Fancybox.getInstance?.();
  //   if (inst) inst.close();
  //
  //   Fancybox.show([{ src: "#" + id, type: "inline" }], { dragToClose: false, ...opts });
  // }

//   Использование
//  // по data-атрибутам
//   document.addEventListener("wpcf7mailsent", () => fancyShow("[data-message-success]"));
//   document.addEventListener("wpcf7mailfailed", () => fancyShow("[data-message-error]"));
//
// // напрямую по элементу
//   const el = document.querySelector("#modal-success");
//   fancyShow(el);
//
// // напрямую по id
//   fancyShow("#modal-success");

};
