#!/usr/bin/env python3
"""
generate_media.py

Create AtomicFlix poster, banner, and thumbnail assets from one source image.

Usage:
  python scripts/generate_media.py --source path/to/source.png --target city-at-worlds-end/ch03.jpg

Outputs:
  assets/posters/city-at-worlds-end/ch03.jpg
  assets/banners/city-at-worlds-end/ch03.jpg
  assets/thumbnails/city-at-worlds-end/ch03.jpg
"""

from __future__ import annotations

import argparse
import sys
from pathlib import Path
from typing import Tuple

from PIL import Image, ImageOps

SPECS = {
    "posters": (300, 450),
    "banners": (1920, 1080),
    "thumbnails": (1280, 720),
}

CENTERING_MAP = {
    "top": (0.5, 0.0),
    "center": (0.5, 0.5),
    "bottom": (0.5, 1.0),
}


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Generate AtomicFlix poster, banner, and thumbnail assets from a single source image."
    )
    parser.add_argument(
        "--source",
        required=True,
        help="Path to the source image."
    )
    parser.add_argument(
        "--target",
        required=True,
        help=(
            "Relative output filename to use under assets/posters, assets/banners, "
            "and assets/thumbnails. Example: city-at-worlds-end/ch03.jpg"
        )
    )
    parser.add_argument(
        "--assets-root",
        default="assets",
        help="Root assets directory. Default: assets"
    )
    parser.add_argument(
        "--focus",
        choices=["top", "center", "bottom"],
        default="center",
        help="Crop focus for cover-fit resizing. Default: center"
    )
    parser.add_argument(
        "--quality",
        type=int,
        default=92,
        help="JPEG/WebP quality (1-100). Default: 92"
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Show what would be written without creating files."
    )
    return parser.parse_args()


def ensure_rgb_for_output(image: Image.Image, suffix: str) -> Image.Image:
    """
    Convert image modes when the target format does not support alpha cleanly.
    """
    suffix = suffix.lower()
    if suffix in {".jpg", ".jpeg"}:
        if image.mode not in ("RGB", "L"):
            bg = Image.new("RGB", image.size, (0, 0, 0))
            if image.mode in ("RGBA", "LA"):
                bg.paste(image, mask=image.getchannel("A"))
            else:
                bg.paste(image)
            return bg
        if image.mode == "L":
            return image.convert("RGB")
    return image


def fit_cover(image: Image.Image, size: Tuple[int, int], focus: str) -> Image.Image:
    """
    Resize and crop to exact dimensions using a 'cover' strategy.
    """
    return ImageOps.fit(
        image,
        size,
        method=Image.Resampling.LANCZOS,
        centering=CENTERING_MAP[focus],
    )


def save_image(image: Image.Image, path: Path, quality: int) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    suffix = path.suffix.lower()

    image = ensure_rgb_for_output(image, suffix)

    save_kwargs = {}
    if suffix in {".jpg", ".jpeg"}:
        save_kwargs = {
            "quality": quality,
            "optimize": True,
            "progressive": True,
        }
    elif suffix == ".webp":
        save_kwargs = {
            "quality": quality,
            "method": 6,
        }
    elif suffix == ".png":
        save_kwargs = {
            "optimize": True,
        }

    image.save(path, **save_kwargs)


def normalize_target_filename(target: str) -> Path:
    path = Path(target)
    if not path.suffix:
        path = path.with_suffix(".jpg")
    return path


def main() -> int:
    args = parse_args()

    source_path = Path(args.source)
    if not source_path.is_file():
        print(f"ERROR: Source file not found: {source_path}", file=sys.stderr)
        return 1

    target_rel = normalize_target_filename(args.target)
    assets_root = Path(args.assets_root)

    try:
        with Image.open(source_path) as img:
            img = ImageOps.exif_transpose(img)

            outputs = []
            for folder, size in SPECS.items():
                out_path = assets_root / folder / target_rel
                outputs.append((folder, size, out_path))

            if args.dry_run:
                print("Dry run:")
                for folder, size, out_path in outputs:
                    print(f"  {folder:11s} {size[0]}x{size[1]} -> {out_path}")
                return 0

            for folder, size, out_path in outputs:
                derived = fit_cover(img, size, args.focus)
                save_image(derived, out_path, args.quality)
                print(f"Created {folder:11s} {size[0]}x{size[1]} -> {out_path}")

    except Exception as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        return 1

    return 0


if __name__ == "__main__":
    raise SystemExit(main())