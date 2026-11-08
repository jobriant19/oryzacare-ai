"""
Periksa penyaring pada gambar Anda sendiri.

Pemakaian:
    python calibrate.py <folder>
    contoh: python calibrate.py D:/dataset_padi
<folder> berisi sub-folder (Blast, Tungro, ... atau folder uji berisi ilustrasi/logo) berisi gambar.

Cara membaca:
  - Untuk folder FOTO ASLI (dataset): kolom grafis dan bukan_daun harus mendekati 0%,
    dan tanpa_gejala sebaiknya di bawah ~5%.
  - Untuk folder ILUSTRASI/LOGO/ANIMASI uji: kolom grafis + bukan_daun sebaiknya tinggi.
  Jika tidak sesuai, ubah ambang di bagian atas predict.py.
"""
import os
import sys

import cv2

from predict import (color_metrics, graphic_metrics, judge,
                     ALPHA_MAX, CONST_MAX, MIN_PLANT, HEALTHY_MIN_GREEN, HEALTHY_MAX_LESION)

EXT = (".jpg", ".jpeg", ".png", ".webp")


def main():
    if len(sys.argv) < 2 or not os.path.isdir(sys.argv[1]):
        print("Pemakaian: python calibrate.py <folder>")
        sys.exit(1)
    root = sys.argv[1]
    print(f"Ambang: ALPHA_MAX={ALPHA_MAX} CONST_MAX={CONST_MAX} MIN_PLANT={MIN_PLANT} "
          f"HEALTHY_MIN_GREEN={HEALTHY_MIN_GREEN} HEALTHY_MAX_LESION={HEALTHY_MAX_LESION}\n")
    print(f"{'kelas':18s}{'jumlah':>8s}{'grafis':>10s}{'bukan_daun':>13s}{'tanpa_gejala':>14s}")

    for kelas in sorted(os.listdir(root)):
        folder = os.path.join(root, kelas)
        if not os.path.isdir(folder):
            continue
        n = grafis = warna = tanpa = 0
        for nama in os.listdir(folder):
            if not nama.lower().endswith(EXT):
                continue
            path = os.path.join(folder, nama)
            img = cv2.imread(path)
            if img is None:
                continue
            status, alasan = judge(color_metrics(img), graphic_metrics(path, img))
            n += 1
            grafis += (status == "not_leaf" and alasan == "graphic")
            warna += (status == "not_leaf" and alasan == "color")
            tanpa += (status == "no_symptoms")
        if n:
            print(f"{kelas:18s}{n:8d}{grafis / n:10.1%}{warna / n:13.1%}{tanpa / n:14.1%}")


if __name__ == "__main__":
    main()
