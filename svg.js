#!/usr/bin/env node
"use strict"

import svgstore from "svgstore"
import { readFileSync, writeFileSync } from "fs"
import { globSync } from "glob"
import { resolve, relative } from "path"
import { optimize } from "svgo"

const sprite = svgstore()
const files = globSync("src/icons/**/*.svg")

const svgoConfig = {
	plugins: [
		{
			name: "preset-default",
		},
		{
			name: "removeDimensions",
		},
	],
}

for (let file of files) {
	const result = optimize(readFileSync(resolve(file), 'utf8'), svgoConfig)
	sprite.add(
		relative("src/icons", file).replace(/.svg$/i, ""),
		result.data
	)
}

writeFileSync(resolve("src/sprite.svg"), sprite.toString())
