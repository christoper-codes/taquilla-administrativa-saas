export default function useObjectsFormat() {

    const findPropertyInObject = (obj, property, coincidence = 1, defaultValue = null) => {

        let count = 0;

        if (typeof obj !== 'object' || obj === null) {
            return defaultValue;
        } ;

        if (obj.hasOwnProperty(property)) {
            count++;

            if (count === coincidence) {
                return obj[property];
            }
        }

        for (const key in obj) {
            if (typeof obj[key] === 'object') {
                const result = findPropertyInObject(obj[key], property, coincidence, defaultValue);
                if (result !== defaultValue) {
                    return result;
                }
            }else if(Array.isArray(obj[key])){

                for (const item of obj[key]) {
                    const result = findPropertyInObject(item, property, coincidence, defaultValue);
                    if (result !== defaultValue) {
                        return result;
                    }
                }

            }
        }
        return defaultValue;
    };

    const cloneObject = (obj) => {

        if (typeof obj !== 'object' || obj === null) {
            return null;
        };

        return JSON.parse(JSON.stringify(obj));
    };

    const detectChangesInArraysOrObjects = (oldArray, newArray) => {
        const changes = [];

        // Asegurarse de que ambos arrays tengan la misma longitud
        if (oldArray.length !== newArray.length) {
          throw new Error("Both arrays must have the same length");
        }

        for (let i = 0; i < oldArray.length; i++) {
          const oldItem = oldArray[i];
          const newItem = newArray[i];

          // Comparar cada propiedad del objeto
          for (const key in oldItem) {
            if (oldItem[key] !== newItem[key]) {
              changes.push({
                index: i,
                oldValue: oldItem,
                newValue: newItem
              });
            }
          }
        }

        return changes;
      }

    return {
        findPropertyInObject,
        cloneObject,
        detectChangesInArraysOrObjects
    }
}
