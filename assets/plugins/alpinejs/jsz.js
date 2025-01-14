(function() {
    function o(f) {
      Alpine.directive("jsz", (el, _, { evaluate, effect }) => {

        const unescapeIt = (str) => {
          let textarea = document.createElement("textarea");
          textarea.innerHTML = str;
          return textarea.value;
        }
        const finder = new RegExp(`{{([^{}]*)}}`, "g");

        const evaluateIt = (str) => {
          str.match(finder)?.map((match) => {
            str = str.replace(match, evaluate(unescapeIt(match.replace(finder, "$1"))) || "");
          });
          return str || "";
        }

        let targetEl = el;
        let template = el.innerHTML;

        if (el.tagName === "TEMPLATE") {
          targetEl = el.content.firstElementChild.cloneNode();
          template =  el.content.firstElementChild.innerHTML;
          el.after(targetEl);
        }

        effect(() => {
          targetEl.innerHTML = evaluateIt(template);
        })

      });
    }
    document.addEventListener("alpine:init", function() {
        window.Alpine.plugin(o)
    });
}
)();
