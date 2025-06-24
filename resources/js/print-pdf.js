import pkg from 'pdf-to-printer';
const { print } = pkg;

const file = process.argv[2];
const printerName = process.argv[3];

let options = {
    printer: printerName,
    scale: 'fit',
    paperSize: 'A6',
    silent: true,
};

print(file, options)
  .then(() => console.log("Impresión completada"))
  .catch(console.error);
