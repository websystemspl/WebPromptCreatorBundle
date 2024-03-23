import { reactive, defineAsyncComponent, markRaw } from 'vue'

export const contentStore = reactive({
  content: [],

  removeElement(uid) {
    this.removeElementByUid(contentStore.content, uid)
  },

  createComponent(name)
  {
      return markRaw(defineAsyncComponent(() => {
        return import(`./../widgets/${name}.vue`);
      }));
  },

  removeElementByUid(elements, uidToRemove) {
    for (let i = 0; i < elements.length; i++) {
      const element = elements[i];
      if (element.uid === uidToRemove) {
        elements.splice(i, 1);
        return true; 
      } else if (element.widgets && element.widgets.length) {
        if (this.removeElementByUid(element.widgets, uidToRemove)) {
          return true; 
        }
      }
    }
    return false;
  },

  recursiveReplaceComponent(elements) {
    elements.forEach(element => {
      if(element.component.object) {
        element.component = {
          name: element.component.name
        }
      }

      if (element.widgets && element.widgets.length) {
        this.recursiveReplaceComponent(element.widgets);
      }
    });
    return elements;
  },

  findPreviousRootElement(data, uid) {
    let parentIndex = -1; // Indeks elementu nadrzędnego w tablicy root

    // Funkcja do przeszukiwania rekurencyjnego
    function search(data, currentLevel = 0) {
        for (let i = 0; i < data.length; i++) {
            // Jeśli znaleziono UID
            if (data[i].uid === uid) {
                return { found: true, level: currentLevel, index: i };
            }

            // Przeszukiwanie podelementów (widgets)
            if (data[i].widgets && data[i].widgets.length > 0) {
                const result = search(data[i].widgets, currentLevel + 1);
                if (result.found) {
                    // Jeśli znaleziono w podelementach, zwróć informacje na wyższym poziomie
                    if (currentLevel === 0) parentIndex = i; // Zapisz indeks nadrzędnego elementu root, jeśli jesteśmy na poziomie 0
                    return result;
                }
            }
        }
        return { found: false };
    }

    const searchResult = search(data);
    if (!searchResult.found) return null; // Nie znaleziono elementu z podanym UID

    // Znalezienie i zwrócenie poprzedniego elementu root, jeśli istnieje
    const previousRoot = parentIndex > 0 ? data[parentIndex - 1] : null;
    return previousRoot;
  },

  findPreviousRootElements(data, uid) {
    let parentIndex = -1; // Indeks elementu nadrzędnego w tablicy root
    let previousRoots = []; // Tablica poprzednich elementów root

    // Funkcja do przeszukiwania rekurencyjnego
    function search(data, currentLevel = 0) {
        for (let i = 0; i < data.length; i++) {
            // Jeśli znaleziono UID
            if (data[i].uid === uid) {
                return { found: true, level: currentLevel, index: i };
            }

            // Przeszukiwanie podelementów (widgets)
            if (data[i].widgets && data[i].widgets.length > 0) {
                const result = search(data[i].widgets, currentLevel + 1);
                if (result.found) {
                    // Jeśli znaleziono w podelementach, zwróć informacje na wyższym poziomie
                    if (currentLevel === 0) parentIndex = i; // Zapisz indeks nadrzędnego elementu root, jeśli jesteśmy na poziomie 0
                    return result;
                }
            }
        }
        return { found: false };
    }

    const searchResult = search(data);
    if (!searchResult.found) return null; // Nie znaleziono elementu z podanym UID

    // Znalezienie i zwrócenie poprzednich elementów root, jeśli istnieją
    for (let i = 0; i < parentIndex; i++) {
        previousRoots.push(data[i]);
    }
    return previousRoots;
  },

  findElementByUid(elements, uid) {
    for (let i = 0; i < elements.length; i++) {
      const element = elements[i];
      if (element.uid === uid) {
        return element;
      } else if (element.widgets && element.widgets.length) {
        const foundElement = this.findElementByUid(element.widgets, uid);
        if (foundElement) {
          return foundElement;
        }
      }
    }
    return null;
  },

  generateUid() {
    return Math.random().toString(36).substring(7);
  },

  cloneElement(element) {
    let elem = JSON.parse(JSON.stringify(element));
    elem.uid = this.generateUid();
    return elem;
  },

  cloneElementWithWidgets(element) {
    let elem = this.cloneElement(element);
    if (elem.widgets && elem.widgets.length) {
      elem.widgets = elem.widgets.map(widget => {
        return this.cloneElementWithWidgets(widget);
      });
    }
    return elem;
  },

  findAndDuplicateByUID(uid) {
    const duplicate = (arr) => {
      for (let i = 0; i < arr.length; i++) {
        if (arr[i].uid === uid) {
          const elementCopy = this.cloneElementWithWidgets(arr[i]);
          arr.splice(i + 1, 0, elementCopy);
          return true;
        }
        // Sprawdzenie, czy widget posiada własne widgety, i zastosowanie rekursji
        if (arr[i].widgets) {
          const foundAndDuplicated = duplicate(arr[i].widgets);
          if (foundAndDuplicated) return true; // Jeśli znaleziono i zduplikowano widget, nie ma potrzeby dalszego przeszukiwania
        }
      }
      return false; // Zwróć false, jeśli uid nie zostało znalezione na danym poziomie zagnieżdżenia
    };
  
    duplicate(contentStore.content);
  }
})