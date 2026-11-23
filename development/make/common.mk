guard-%:
	@if [ -z '${${*}}' ]; then \
		echo "Variable $* not set" && exit 1; \
	fi

define COPY_ENV
	@if [ -f $@ ]; then \
		echo "File $@ exists. Update manually or delete it and rerun the command."; \
	else \
		cp $< $@; \
		echo "File $@ created. Do not forget to fill it with values manually."; \
	fi
endef
