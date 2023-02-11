const units = ["b", "KiB", "MiB", "GiB", "TiB", "PiB"]
const len_units = units.length - 1;

function storeConvert(size) {
  if (size <= 0) {
      return "0";
  }

  let idx = 0;
  while (idx < len_units && size > 1024) {
    console.log(idx, size)
    size /= 1024;
    idx ++;
  }
  return `${size.toFixed(1)} ${units[idx]}`;
}

module.exports = {
  storeConvert: storeConvert,
}