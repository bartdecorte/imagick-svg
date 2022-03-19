# Release notes for 1.x

## [1.2.1](https://github.com/bartdecorte/imagick-svg/compare/1.2.0...1.2.1) - 2022-03-19

### Fixed
- x, y, cx, cy attribute defaults for circles, ellipses & rectangles
- the optional 2nd & 3rd arguments for a rotation transformation, which were previously ignored, are now taken into
account
- skewY transformations are now expressed through a matrix transformation, fixing unexpected Imagick behaviour

## [1.2.0](https://github.com/bartdecorte/imagick-svg/compare/1.1.0...1.2.0) - 2022-03-15

### Added
- Support for `<g>` group elements

### Fixed
- Division by zero while calculating an inverse transformation matrix

### Changed
- preg calls are replaced by an XMLReader instance
- An exception is no longer thrown for unsupported elements, they are simply ignored

## [1.1.0](https://github.com/bartdecorte/imagick-svg/compare/1.0.1...1.1.0) - 2022-03-15

### Added
- Transform "translate" support
- Transform "scale" support
- Transform "rotate" support
- Transform "skewX" & "skewY" support
- Throw exceptions for unsupported elements/tranforms

### Fixed
- Revert transforms after executing draw instructions for the current element
